<?php

namespace tests\ContentTests;


use kalanis\kw_images\Sources;


class XSourceImageOrientationFail extends Sources\Image
{
    public function get(array $path)
    {
        return '';
    }
}
