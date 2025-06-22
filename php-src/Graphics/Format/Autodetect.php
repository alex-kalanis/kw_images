<?php

namespace kalanis\kw_images\Graphics\Format;


use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IIMTranslations;


/**
 * Class Autodetect
 * Get image using autodetection; cannot save this way, it needs to tell which format will be used
 * @package kalanis\kw_images\Graphics\Format
 */
class Autodetect extends AFormat
{
    /**
     * @param IIMTranslations|null $lang
     * @throws ImagesException
     */
    public function __construct(?IIMTranslations $lang = null)
    {
        $this->setImLang($lang);
        if (!function_exists('imagecreatefromstring')) {
            // @codeCoverageIgnoreStart
            throw new ImagesException($this->getImLang()->imImageMagicLibNotPresent());
        }
        // @codeCoverageIgnoreEnd
    }

    public function load(string $path)
    {
        $content = @file_get_contents($path);
        if (empty($content)) {
            throw new ImagesException($this->getImLang()->imCannotCreateFromResource());
        }
        $result = @imagecreatefromstring($content);
        if (false === $result) {
            throw new ImagesException($this->getImLang()->imCannotCreateFromResource());
        }
        return $result;
    }

    public function save(?string $path, $resource): void
    {
        throw new ImagesException($this->getImLang()->imCannotSaveResource());
    }
}
