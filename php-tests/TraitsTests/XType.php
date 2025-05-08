<?php

namespace tests\TraitsTests;


use kalanis\kw_images\Traits\TType;


class XType
{
    use TType;

    /**
     * @param string[] $path
     * @throws \kalanis\kw_images\ImagesException
     * @throws \kalanis\kw_mime\MimeException
     * @return string
     */
    public function xGetType(array $path): string
    {
        return $this->getType($path);
    }
}
