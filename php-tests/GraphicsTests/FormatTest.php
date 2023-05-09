<?php

namespace GraphicsTests;


use CommonTestClass;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Translations;


class FormatTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.bmp';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.gif';
        $tgt3 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.jpg';
        $tgt4 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.wbmp';
        $tgt5 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.webp';
        $tgt6 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.avif';
        $tgt7 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.xbm';
        if (is_file($tgt0)) {
            unlink($tgt0);
        }
        if (is_file($tgt1)) {
            unlink($tgt1);
        }
        if (is_file($tgt2)) {
            unlink($tgt2);
        }
        if (is_file($tgt3)) {
            unlink($tgt3);
        }
        if (is_file($tgt4)) {
            unlink($tgt4);
        }
        if (is_file($tgt5)) {
            unlink($tgt5);
        }
        if (is_file($tgt6)) {
            unlink($tgt6);
        }
        if (is_file($tgt7)) {
            unlink($tgt7);
        }
    }

    /**
     * @param string $type
     * @throws ImagesException
     * @dataProvider factoryProvider
     * @requires function imagecreatefrompng
     * @requires function imagepng
     * @requires function imagecreatefrombmp
     * @requires function imagebmp
     * @requires function imagecreatefromjpeg
     * @requires function imagejpeg
     */
    public function testFactoryPass(string $type): void
    {
        $lib = new Format\Factory();
        $this->assertInstanceOf(Format\AFormat::class, $lib->getByType($type, new Translations()));
    }

    public function factoryProvider(): array
    {
        return [
            ['png'],
            ['bmp'],
            ['jpeg'],
        ];
    }

    public function testFactoryTypeFail(): void
    {
        $lib = new Format\Factory();
        $this->expectExceptionMessage('Unknown type *txt*');
        $this->expectException(ImagesException::class);
        $lib->getByType('txt', new Translations());
    }

    public function testFactoryClassFail(): void
    {
        $lib = new XFactory();
        $this->expectExceptionMessage('Wrong instance of *stdClass*, must be instance of \kalanis\kw_images\Graphics\Format\AFormat');
        $this->expectException(ImagesException::class);
        $lib->getByType('xxx', new Translations());
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefrompng
     * @requires function imagepng
     */
    public function testContentPng(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $this->contentTesting(new Format\Png(), $tgt0);
    }

    /**
     * @throws ImagesException
     * Slow test, I know about it
     * @requires function imagecreatefrombmp
     * @requires function imagebmp
     */
    public function testContentBmp(): void
    {
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.bmp';
        $this->contentTesting(new Format\Bmp(), $tgt1);
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromgif
     * @requires function imagegif
     */
    public function testContentGif(): void
    {
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.gif';
        $this->contentTesting(new Format\Gif(), $tgt2);
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromjpeg
     * @requires function imagejpeg
     */
    public function testContentJpg(): void
    {
        $tgt3 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.jpg';
        $this->contentTesting(new Format\Jpeg(), $tgt3);
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromwbmp
     * @requires function imagewbmp
     */
    public function testContentWbmp(): void
    {
        $tgt4 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.wbmp';
        $this->contentTesting(new Format\Wbmp(), $tgt4);
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromwebp
     * @requires function imagewebp
     */
    public function testContentWebp(): void
    {
        $tgt5 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.webp';
        $this->contentTesting(new Format\Webp(), $tgt5);
    }

//    /**
//     * @throws ImagesException
//     * @requires function imagecreatefromavif
//     * @requires function imageavif
//     */
//    public function testContentAvif(): void
//    {
//        $tgt6 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.avif';
//        $this->contentTesting(new Format\Avif(), $tgt6);
//    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromxbm
     * @requires function imagexbm
     */
    public function testContentXbm(): void
    {
        $tgt7 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.xbm';
        $this->contentTesting(new Format\Xbm(), $tgt7);
    }

    /**
     * @param Format\AFormat $lib
     * @param string $target
     * @throws ImagesException
     */
    protected function contentTesting(Format\AFormat $lib, string $target): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $libSrc = new Format\Png();

        $resSrc = $libSrc->load($src);
        $this->assertNotEmpty($resSrc);

        $lib->save($target, $resSrc);
        $this->assertTrue(file_exists($target));

        $res = $lib->load($target);
        $this->assertNotEmpty($res);
    }
}


class XFactory extends Format\Factory
{
    protected $types = [
        'bmp' => Format\Bmp::class,
        'xxx' => \stdClass::class,
    ];
}
