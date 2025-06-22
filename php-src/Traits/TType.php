<?php

namespace kalanis\kw_images\Traits;


use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_mime\Interfaces\IMime;
use kalanis\kw_mime\MimeException;


/**
 * Trait TType
 * Work with different image types
 * @package kalanis\kw_images\Traits
 */
trait TType
{
    use TLang;

    protected ?IMime $libMime = null;

    public function initType(IMime $libMime, ?IIMTranslations $lang = null): void
    {
        $this->setImLang($lang);
        $this->libMime = $libMime;
    }

    /**
     * @param string[] $path
     * @throws ImagesException
     * @throws MimeException
     * @return string
     */
    protected function getType(array $path): string
    {
        $mime = $this->getMimeType()->getMime($path);
        list($type, $app) = explode('/', $mime);
        if ('image' !== $type) {
            throw new ImagesException($this->getImLang()->imWrongMime($mime), ImagesException::TRAIT_WRONG_MIME);
        }
        return $app;
    }

    /**
     * @throws ImagesException
     * @return IMime
     */
    private function getMimeType(): IMime
    {
        if (empty($this->libMime)) {
            throw new ImagesException($this->getImLang()->imUnknownMime(), ImagesException::TRAIT_UNKNOWN_MIME);
        }
        return $this->libMime;
    }
}
