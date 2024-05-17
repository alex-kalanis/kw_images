<?php

namespace ConfigsTests;


use CommonTestClass;
use kalanis\kw_images\Configs;


class ConfigsTest extends CommonTestClass
{
    public function testProcessor(): void
    {
        $conf = new Configs\ProcessorConfig();
        $conf->setData([
            'create_thumb' => 0,
            'want_limit_size' => null,
            'want_limit_ext' => 1,
            'default_ext' => 'oof',
            'max_upload_width' => 333,
            'max_upload_height' => 222,
            'max_size' => 951357456,
            'max_width' => 120,
            'max_height' => 80,
            'tmb_width' => 60,
            'tmb_height' => 50,
        ]);
        $this->assertEquals(false, $conf->hasThumb());
        $this->assertEquals(false, $conf->canLimitSize());
        $this->assertEquals(true, $conf->canLimitExt());
        $this->assertEquals('oof', $conf->getDefaultExt());
    }

    public function testImage(): void
    {
        $conf = new Configs\ImageConfig();
        $conf->setData([
            'create_thumb' => 0,
            'want_limit_size' => null,
            'want_limit_ext' => 1,
            'default_ext' => 'oof',
            'max_upload_width' => 333,
            'max_upload_height' => 222,
            'max_size' => 951357456,
            'max_width' => 120,
            'max_height' => 80,
            'tmb_width' => 60,
            'tmb_height' => 50,
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
        $conf = new Configs\ThumbConfig();
        $conf->setData([
            'create_thumb' => 0,
            'want_limit_size' => null,
            'want_limit_ext' => 1,
            'default_ext' => 'oof',
//            'max_upload_width' => 333,
//            'max_upload_height' => 222,
            'max_size' => 951357456,
            'max_width' => 120,
            'max_height' => 80,
            'tmb_width' => 60,
            'tmb_height' => 50,
        ]);
        $this->assertEquals(16384, $conf->getMaxInWidth());
        $this->assertEquals(16384, $conf->getMaxInHeight());
        $this->assertEquals(60, $conf->getMaxStoreWidth());
        $this->assertEquals(50, $conf->getMaxStoreHeight());
        $this->assertEquals(10485760, $conf->getMaxFileSize());
        $this->assertEquals('thumb_tmp', $conf->getTempPrefix());
    }
}
