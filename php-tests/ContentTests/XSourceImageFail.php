<?php

namespace tests\ContentTests;


use kalanis\kw_images\Sources;


class XSourceImageFail extends Sources\Image
{
    public function get(array $path)
    {
        return '';
    }
}
