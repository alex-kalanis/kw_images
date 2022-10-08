<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_images\Graphics;


class ConfsTest extends CommonTestClass
{
    public function testImage(): void
    {
        $conf = new Graphics\ImageConfig();
        $conf->setData(['max_width' => 120, 'max_height' => 80, 'tmb_width' => 60, 'tmb_height' => 50]);
        $this->assertEquals(120, $conf->getMaxWidth());
        $this->assertEquals(80, $conf->getMaxHeight());
        $this->assertEquals(10485760, $conf->getMaxSize());
        $this->assertEquals('', $conf->getTempPrefix());
    }

    public function testThumb(): void
    {
        $conf = new Graphics\ThumbConfig();
        $conf->setData(['max_width' => 120, 'max_height' => 80, 'tmb_width' => 60, 'tmb_height' => 50]);
        $this->assertEquals(60, $conf->getMaxWidth());
        $this->assertEquals(50, $conf->getMaxHeight());
        $this->assertEquals(10485760, $conf->getMaxSize());
        $this->assertEquals('thumb_tmp', $conf->getTempPrefix());
    }
}
