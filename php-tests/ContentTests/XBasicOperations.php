<?php

namespace tests\ContentTests;


use kalanis\kw_images\Content\BasicOperations;
use kalanis\kw_images\Sources;


class XBasicOperations extends BasicOperations
{
    public function getLibImage(): Sources\Image
    {
        return $this->libImage;
    }

    public function getLibThumb(): Sources\Thumb
    {
        return $this->libThumb;
    }

    public function getLibDesc(): Sources\Desc
    {
        return $this->libDesc;
    }
}
