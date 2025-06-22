<?php

namespace tests\GraphicsTests;


use kalanis\kw_images\Graphics;


class XGraphics extends Graphics
{
    public function __construct(Graphics\Processor $libGraphics)
    {
        // intentionally without parent - need to fail on lib Mime
        $this->libGraphics = $libGraphics;
    }
}
