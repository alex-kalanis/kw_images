<?php

namespace tests\TraitsTests;


use kalanis\kw_images\Traits\TSizes;


class XSizes
{
    use TSizes;

    public function xCalculateSize(int $currentWidth, int $maxWidth, int $currentHeight, int $maxHeight)
    {
        return $this->calculateSize($currentWidth, $maxWidth, $currentHeight, $maxHeight);
    }
}
