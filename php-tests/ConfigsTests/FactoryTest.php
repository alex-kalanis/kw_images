<?php

namespace tests\ConfigsTests;


use tests\CommonTestClass;
use kalanis\kw_images\Configs;


class FactoryTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $factory = new Configs\ConfigFactory();
        $factory->setData(['images' => [
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
        ]]);

        $conf1 = $factory->getProcessor();
        $this->assertEquals(false, $conf1->hasThumb());
        $this->assertEquals(false, $conf1->canLimitSize());
        $this->assertEquals(true, $conf1->canLimitExt());
        $this->assertEquals('oof', $conf1->getDefaultExt());

        $conf2 = $factory->getImage();
        $this->assertEquals(333, $conf2->getMaxInWidth());
        $this->assertEquals(222, $conf2->getMaxInHeight());
        $this->assertEquals(120, $conf2->getMaxStoreWidth());
        $this->assertEquals(80, $conf2->getMaxStoreHeight());
        $this->assertEquals(951357456, $conf2->getMaxFileSize());
        $this->assertEquals('', $conf2->getTempPrefix());

        $conf3 = $factory->getThumb();
        $this->assertEquals(333, $conf3->getMaxInWidth());
        $this->assertEquals(222, $conf3->getMaxInHeight());
        $this->assertEquals(60, $conf3->getMaxStoreWidth());
        $this->assertEquals(50, $conf3->getMaxStoreHeight());
        $this->assertEquals(10485760, $conf3->getMaxFileSize());
        $this->assertEquals('thumb_tmp', $conf3->getTempPrefix());
    }
}
