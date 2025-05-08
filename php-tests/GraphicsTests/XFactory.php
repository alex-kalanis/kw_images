<?php

namespace tests\GraphicsTests;


use kalanis\kw_images\Graphics\Format;


class XFactory extends Format\Factory
{
    protected array $types = [
        'bmp' => Format\Bmp::class,
        'xxx' => NotFormat::class,
        'not_class' => 'this_is_not_a_class',
    ];
}
