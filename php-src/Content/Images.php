<?php

namespace kalanis\kw_images\Content;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;


/**
 * Class Images
 * Process images parts
 * @package kalanis\kw_images\Content
 */
class Images
{
    /** @var ImageSize */
    protected $libSizes = null;
    /** @var Sources\Image */
    protected $libImage = null;
    /** @var Sources\Thumb */
    protected $libThumb = null;
    /** @var Sources\Desc */
    protected $libDesc = null;

    public function __construct(ImageSize $sizes, Sources\Image $image, Sources\Thumb $thumb, Sources\Desc $desc)
    {
        $this->libSizes = $sizes;
        $this->libImage = $image;
        $this->libThumb = $thumb;
        $this->libDesc = $desc;
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @return resource|string
     */
    public function get(array $wantedPath)
    {
        try {
            return $this->libImage->get($wantedPath);
        } catch (FilesException $ex) {
            return '';
        }
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @param string|resource $content what we want to store as the file
     * @throws FilesException
     * @return bool
     */
    public function set(array $wantedPath, $content): bool
    {
        return $this->libImage->set($wantedPath, $content);
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @throws FilesException
     * @return bool
     */
    public function remove(array $wantedPath): bool
    {
        $fileName = strval(array_pop($wantedPath));
        return $this->libImage->delete($wantedPath, $fileName);
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @return resource|string
     */
    public function getThumb(array $wantedPath)
    {
        try {
            return $this->libThumb->get($wantedPath);
        } catch (FilesException $ex) {
            return '';
        }
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @throws FilesException
     * @throws ImagesException
     * @return bool
     */
    public function updateThumb(array $wantedPath): bool
    {
        return $this->libSizes->process(
            $this->libImage->getPath($wantedPath),
            $this->libThumb->getPath($wantedPath)
        );
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @throws FilesException
     * @return bool
     */
    public function removeThumb(array $wantedPath): bool
    {
        $fileName = strval(array_pop($wantedPath));
        return $this->libThumb->delete($wantedPath, $fileName);
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @return string
     */
    public function getDescription(array $path): string
    {
        return $this->libDesc->get($path, false);
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @param string $description
     * @throws FilesException
     * @return bool
     */
    public function updateDescription(array $wantedPath, string $description = ''): bool
    {
        if (!empty($description)) {
            return $this->libDesc->set($wantedPath, $description);
        } else {
            $fileName = strval(array_pop($wantedPath));
            return $this->libDesc->delete($wantedPath, $fileName);
        }
    }
}
