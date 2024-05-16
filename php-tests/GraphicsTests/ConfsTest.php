<?php

namespace GraphicsTests;


use CommonTestClass;
use kalanis\kw_images\Graphics;


class ConfsTest extends CommonTestClass
{
    public function testImage(): void
    {
        $conf = new Graphics\ImageConfig();
        $conf->setData([
            'max_upload_width' => 333,
            'max_upload_height' => 222,
            'max_size' => 951357456,
            'max_width' => 120,
            'max_height' => 80,
            'tmb_width' => 60,
            'tmb_height' => 50
        ]);
        $this->assertEquals(333, $conf->getMaxInWidth());
        $this->assertEquals(222, $conf->getMaxInHeight());
        $this->assertEquals(120, $conf->getMaxStoreWidth());
        $this->assertEquals(80, $conf->getMaxStoreHeight());
        $this->assertEquals(951357456, $conf->getMaxFileSize());
        $this->assertEquals('', $conf->getTempPrefix());
    }

    public function testThumb(): void
    {
        $conf = new Graphics\ThumbConfig();
        $conf->setData([
            'max_width' => 120,
            'max_height' => 80,
            'tmb_width' => 60,
            'tmb_height' => 50
        ]);
        $this->assertEquals(16384, $conf->getMaxInWidth());
        $this->assertEquals(16384, $conf->getMaxInHeight());
        $this->assertEquals(60, $conf->getMaxStoreWidth());
        $this->assertEquals(50, $conf->getMaxStoreHeight());
        $this->assertEquals(10485760, $conf->getMaxFileSize());
        $this->assertEquals('thumb_tmp', $conf->getTempPrefix());
    }
}
