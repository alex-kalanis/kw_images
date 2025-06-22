<?php

namespace kalanis\kw_images\Graphics\Format;


use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IIMTranslations;
use ReflectionClass;
use ReflectionException;


/**
 * Class Factory
 * @package kalanis\kw_images\Graphics\Format
 */
class Factory
{
    /** @var array<string, class-string<AFormat>> */
    protected array $types = [
        'autodetect' => Autodetect::class,
        'auto' => Autodetect::class,
        'bmp' => Bmp::class,
        'gif' => Gif::class,
        'jpeg' => Jpeg::class,
        'jpg' => Jpeg::class,
        'png' => Png::class,
        'wbmp' => Wbmp::class,
        'webp' => Webp::class,
        'avif' => Avif::class,
        'xbm' => Xbm::class,
    ];

    /**
     * @param string $type
     * @param IIMTranslations $lang
     * @throws ImagesException
     * @return AFormat
     */
    public function getByType(string $type, IIMTranslations $lang): AFormat
    {
        if (!isset($this->types[$type])) {
            throw new ImagesException($lang->imUnknownType($type), ImagesException::FORMAT_FACTORY_WRONG_FORMAT);
        }
        $className = $this->types[$type];

        try {
            /** @var class-string $className */
            $ref = new ReflectionClass($className);
            $instance = $ref->newInstance($lang);
            if (!$instance instanceof AFormat) {
                throw new ImagesException($lang->imWrongInstance($className), ImagesException::FORMAT_FACTORY_WRONG_TYPE);
            }
            return $instance;
        } catch (ReflectionException $ex) {
            throw new ImagesException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }
}
