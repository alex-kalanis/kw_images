<?php

namespace kalanis\kw_images\Graphics;


use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_images\Traits\TLang;


/**
 * Class Processor
 * @package kalanis\kw_images
 * Pass images in temporary local storage  - cannot work with images directly in main storage
 */
class Processor
{
    use TLang;

    protected Format\Factory $factory;
    /** @var resource|\GdImage|null */
    protected $resource = null;

    /**
     * @param Format\Factory $factory
     * @param IIMTranslations|null $lang
     * @throws ImagesException
     */
    public function __construct(Format\Factory $factory, ?IIMTranslations $lang = null)
    {
        $this->setImLang($lang);

        if (!(function_exists('imagecreatetruecolor')
            && function_exists('imagecolorallocate')
            && function_exists('imagesetpixel')
            && function_exists('imagecopyresized')
            && function_exists('imagecopyresampled')
            && function_exists('imagesx')
            && function_exists('imagesy')
            && function_exists('imagerotate')
            && function_exists('imageflip')
        )) {
            // @codeCoverageIgnoreStart
            throw new ImagesException($this->getImLang()->imGdLibNotPresent(), ImagesException::PROCESSOR_NO_LIBRARY);
        }
        // @codeCoverageIgnoreEnd

        $this->factory = $factory;
    }

    /**
     * @param string $type
     * @param string $tempPath
     * @throws ImagesException
     * @return $this
     */
    public function load(string $type, string $tempPath): self
    {
        $processor = $this->factory->getByType($type, $this->getImLang());
        $this->resource = $processor->load($tempPath);
        return $this;
    }

    /**
     * @param string $type
     * @param string $tempPath
     * @throws ImagesException
     * @return $this
     */
    public function save(string $type, string $tempPath): self
    {
        $processor = $this->factory->getByType($type, $this->getImLang());
        $processor->save($tempPath, $this->getResource());
        return $this;
    }

    /**
     * Change image size - cut it to desired size
     * @param int|null $width
     * @param int|null $height
     * @throws ImagesException
     * @return $this
     */
    public function resize(?int $width = null, ?int $height = null): self
    {
        $fromWidth = $this->width();
        $fromHeight = $this->height();
        $width = (!is_null($width) && (0 < $width)) ? intval($width) : $fromWidth;
        $height = (!is_null($height) && (0 < $height)) ? intval($height) : $fromHeight;
        $resource = $this->create($width, $height);
        try {
            if (false === imagecopyresized($resource, $this->getResource(), 0, 0, 0, 0, $width, $height, $fromWidth, $fromHeight)) {
                // @codeCoverageIgnoreStart
                @imagedestroy($resource);
                throw new ImagesException($this->getImLang()->imImageCannotResize(), ImagesException::PROCESSOR_CANNOT_RESIZE);
            }
            // @codeCoverageIgnoreEnd
        } finally {
            @imagedestroy($this->getResource());
        }
        // @codeCoverageIgnoreEnd
        $this->resource = $resource;
        return $this;
    }

    /**
     * Change image size - content will change its proportions according the passed sizes
     * @param int|null $width
     * @param int|null $height
     * @throws ImagesException
     * @return $this
     */
    public function resample(?int $width = null, ?int $height = null): self
    {
        $fromWidth = $this->width();
        $fromHeight = $this->height();
        $width = (!is_null($width) && (0 < $width)) ? intval($width) : $fromWidth;
        $height = (!is_null($height) && (0 < $height)) ? intval($height) : $fromHeight;
        $resource = $this->create($width, $height);
        try {
            if (false === @imagecopyresampled($resource, $this->getResource(), 0, 0, 0, 0, $width, $height, $fromWidth, $fromHeight)) {
                // @codeCoverageIgnoreStart
                @imagedestroy($resource);
                throw new ImagesException($this->getImLang()->imImageCannotResample(), ImagesException::PROCESSOR_CANNOT_RESAMPLE);
            }
            // @codeCoverageIgnoreEnd
        } finally {
            @imagedestroy($this->getResource());
        }
        // @codeCoverageIgnoreEnd
        $this->resource = $resource;
        return $this;
    }

    /**
     * Rotate image by passed angle
     * @param float $angle
     * @throws ImagesException
     * @return $this
     */
    public function rotate(float $angle): self
    {
        $image = @imagerotate($this->resource, $angle, 0);
        if (empty($image)) {
            // @codeCoverageIgnoreStart
            throw new ImagesException($this->getImLang()->imImageCannotOrientate(), ImagesException::PROCESSOR_CANNOT_ROTATE);
        }
        // @codeCoverageIgnoreEnd
        $this->resource = $image;
        return $this;
    }

    /**
     * Flip image
     * @return $this
     */
    public function flip(int $mode): self
    {
        if (in_array($mode, [IMG_FLIP_HORIZONTAL, IMG_FLIP_VERTICAL, IMG_FLIP_BOTH])) {
            if (empty(@imageflip($this->resource, $mode))) {
                // @codeCoverageIgnoreStart
                throw new ImagesException($this->getImLang()->imImageCannotFlip(), ImagesException::PROCESSOR_CANNOT_FLIP);
            }
            // @codeCoverageIgnoreEnd
        }
        return $this;
    }

    /**
     * Create empty image resource
     * @param int $width
     * @param int $height
     * @throws ImagesException
     * @return \GdImage|resource
     */
    protected function create(int $width, int $height)
    {
        $resource = @imagecreatetruecolor($width, $height);
        if (false === $resource) {
            // @codeCoverageIgnoreStart
            throw new ImagesException($this->getImLang()->imImageCannotCreateEmpty(), ImagesException::PROCESSOR_CANNOT_CREATE);
        }
        // @codeCoverageIgnoreEnd
        return $resource;
    }

    /**
     * @throws ImagesException
     * @return int
     */
    public function width(): int
    {
        $size = @imagesx($this->getResource());
        if (false === $size) {
            // @codeCoverageIgnoreStart
            throw new ImagesException($this->getImLang()->imImageCannotGetSize(), ImagesException::PROCESSOR_CANNOT_GET_SIZE);
        }
        // @codeCoverageIgnoreEnd
        return intval($size);
    }

    /**
     * @throws ImagesException
     * @return int
     */
    public function height(): int
    {
        $size = @imagesy($this->getResource());
        if (false === $size) {
            // @codeCoverageIgnoreStart
            throw new ImagesException($this->getImLang()->imImageCannotGetSize(), ImagesException::PROCESSOR_CANNOT_GET_SIZE);
        }
        // @codeCoverageIgnoreEnd
        return intval($size);
    }

    /**
     * @throws ImagesException
     * @return \GdImage|resource
     */
    public function getResource()
    {
        if (empty($this->resource)) {
            throw new ImagesException($this->getImLang()->imImageLoadFirst(), ImagesException::PROCESSOR_CANNOT_GET_RESOURCE);
        }
        return $this->resource;
    }
}
