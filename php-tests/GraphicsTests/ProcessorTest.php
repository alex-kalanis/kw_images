<?php

namespace tests\GraphicsTests;


use tests\CommonTestClass;
use kalanis\kw_images\Configs;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\Check\CustomList;
use kalanis\kw_mime\MimeException;


class ProcessorTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        if (is_file($tgt0)) {
            unlink($tgt0);
        }
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg1.jpg';
        if (is_file($tgt1)) {
            unlink($tgt1);
        }
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg2.jpg';
        if (is_file($tgt2)) {
            unlink($tgt2);
        }
        $tgt3 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg3.jpg';
        if (is_file($tgt3)) {
            unlink($tgt3);
        }
        $tgt4 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg4.jpg';
        if (is_file($tgt4)) {
            unlink($tgt4);
        }
        $tgt5 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg5.jpg';
        if (is_file($tgt5)) {
            unlink($tgt5);
        }
    }

    /**
     * @throws ImagesException
     */
    public function testSimpleOne(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        $lib = $this->getGraphicsProcessor();
        $lib->load('png', $src);
        $lib->save('png', $tgt0);
        $this->assertTrue(file_exists($tgt0));
        $this->assertEquals(320, $lib->width());
        $this->assertEquals(240, $lib->height());
    }

    /**
     * @throws ImagesException
     */
    public function testNotInit(): void
    {
        $lib = $this->getGraphicsProcessor();
        $this->expectException(ImagesException::class);
        $lib->height();
    }

    /**
     * @throws ImagesException
     */
    public function testFailedOne(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'not-a-image.txt';
        $lib = $this->getGraphicsProcessor();
        $this->expectExceptionMessage('Unknown type *txt*');
        $this->expectException(ImagesException::class);
        $lib->load('txt', $src);
    }

    /**
     * @throws ImagesException
     * @requires function imagecopyresized
     * @requires function imagedestroy
     */
    public function testResize(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        $lib = $this->getGraphicsProcessor();
        $lib->load('png', $src);
        $lib->resize(120, 80);
        $lib->save('png', $tgt0);
        $this->assertTrue(file_exists($tgt0));
        $this->assertEquals(120, $lib->width());
        $this->assertEquals(80, $lib->height());
    }

    /**
     * @throws ImagesException
     * @requires function imagecopyresampled
     * @requires function imagedestroy
     */
    public function testResample(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        $lib = $this->getGraphicsProcessor();
        $lib->load('png', $src);
        $lib->resample(120, 80);
        $lib->save('png', $tgt0);
        $this->assertTrue(file_exists($tgt0));
        $this->assertEquals(120, $lib->width());
        $this->assertEquals(80, $lib->height());
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     * @requires function imagecopyresized
     * @requires function imagedestroy
     */
    public function testResampleFull(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        copy($src, $tgt0); // directly
        $conf = new Configs\ImageConfig();
        $conf->setData(['max_width' => 120, 'max_height' => 80,]);
        $lib = $this->getGraphics();
        $lib->setSizes($conf);
        $lib->resize($tgt0, [$tgt0]);
        $this->assertTrue(file_exists($tgt0));

        $lib2 = $this->getGraphicsProcessor();
        $lib2->load('png', $tgt0);
        $this->assertEquals(106, $lib2->width());
        $this->assertEquals(80, $lib2->height());
        $this->assertEquals('', $conf->getTempPrefix());
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testResizeNoLibs(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $this->expectExceptionMessage('Sizes to compare are not set.');
        $this->expectException(ImagesException::class);
        $lib->resize($src, [$src]);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testResizeBadType(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'textfile.txt';
        $lib = $this->getGraphics();
        $conf = new Configs\ImageConfig();
        $conf->setData(['max_size' => 120000000, 'tmp_pref' => 'fghjkl']);
        $lib->setSizes($conf);
        $this->expectExceptionMessage('Wrong file mime type - got *text/plain*');
        $this->expectException(ImagesException::class);
        $lib->resize($src, [$src]);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testRotateNoValues(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        $lib = $this->getGraphics();
        $conf = new Configs\ImageConfig();
        $conf->setData(['max_size' => 120000000, 'tmp_pref' => 'fghjkl']);
        $lib->setSizes($conf);
        $this->assertFalse($lib->rotate(null, null, $src, [$src], [$tgt0]));
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromjpeg
     * @requires function imagejpeg
     * @requires function imagerotate
     */
    public function testRotateNormal(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage1.jpg';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg1.jpg';
        $lib = $this->getGraphicsProcessor();
        $lib->load('jpg', $src);
        $this->assertEquals(320, $lib->width());
        $this->assertEquals(240, $lib->height());
        $lib->rotate(180);
        $lib->save('jpg', $tgt0);
        $this->assertTrue(file_exists($tgt0));
        $this->assertEquals(320, $lib->width());
        $this->assertEquals(240, $lib->height());
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromjpeg
     * @requires function imagejpeg
     * @requires function imagerotate
     */
    public function testRotate90(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage2.jpg';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg2.jpg';
        $lib = $this->getGraphicsProcessor();
        $lib->load('jpg', $src);
        $this->assertEquals(240, $lib->width());
        $this->assertEquals(320, $lib->height());
        $lib->rotate(90);
        $lib->save('jpg', $tgt0);
        $this->assertTrue(file_exists($tgt0));
        $this->assertEquals(320, $lib->width());
        $this->assertEquals(240, $lib->height());
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromjpeg
     * @requires function imagejpeg
     * @requires function imagerotate
     */
    public function testRotate45(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage3.jpg';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg3.jpg';
        $lib = $this->getGraphicsProcessor();
        $lib->load('jpg', $src);
        $this->assertEquals(320, $lib->width());
        $this->assertEquals(240, $lib->height());
        $lib->rotate(45);
        $lib->save('jpg', $tgt0);
        $this->assertTrue(file_exists($tgt0));
        $this->assertEquals(396, $lib->width());
        $this->assertEquals(396, $lib->height());
    }

    /**
     * @throws ImagesException
     * @requires function imagecreatefromjpeg
     * @requires function imagejpeg
     * @requires function imageflip
     */
    public function testFlip(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage3.jpg';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg3.jpg';
        $lib = $this->getGraphicsProcessor();
        $lib->load('jpg', $src);
        $this->assertEquals(320, $lib->width());
        $this->assertEquals(240, $lib->height());
        $lib->flip(IMG_FLIP_BOTH);
        $lib->save('jpg', $tgt0);
        $this->assertTrue(file_exists($tgt0));
        $this->assertEquals(320, $lib->width());
        $this->assertEquals(240, $lib->height());
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testCheckPass(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $conf = new Configs\ImageConfig();
        $conf->setData(['max_size' => 120000000, 'tmp_pref' => 'fghjkl']);
        $lib = $this->getGraphics();
        $lib->setSizes($conf);
        $this->assertTrue($lib->check($src, ['testimage.png']));
        $this->assertEquals('fghjkl', $conf->getTempPrefix());
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testCheckFailSizeCheck(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $this->expectExceptionMessage('Sizes to compare are not set');
        $this->expectException(ImagesException::class);
        $lib->check($src, ['testimage.png']);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testCheckFailSize(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $lib->setSizes((new Configs\ImageConfig())->setData(['max_size' => 120, ]));
        $this->expectExceptionMessage('This image is too large to use.');
        $this->expectException(ImagesException::class);
        $lib->check($src, ['testimage.png']);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testCheckFailSize2(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $lib->setSizes((new Configs\ImageConfig())->setData(['max_upload_width' => 10, ]));
        $this->expectExceptionMessage('This image is too large to use.');
        $this->expectException(ImagesException::class);
        $lib->check($src, ['testimage.png']);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testCheckFailSize3(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $lib->setSizes((new Configs\ImageConfig())->setData(['max_upload_height' => 10, ]));
        $this->expectExceptionMessage('This image is too large to use.');
        $this->expectException(ImagesException::class);
        $lib->check($src, ['testimage.png']);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testCheckFailSize4(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $this->expectExceptionMessage('Sizes to compare are not set.');
        $this->expectException(ImagesException::class);
        $lib->check($src, ['testimage.png']);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testCheckFailImage(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'not-a-image.txt';
        $lib = $this->getGraphics();
        $lib->setSizes((new Configs\ImageConfig())->setData(['max_size' => 120, ]));
        $this->expectExceptionMessage('Cannot read file size. Exists?');
        $this->expectException(ImagesException::class);
        $lib->check($src, ['testimage.png']);
    }

    /**
     * @throws ImagesException
     * @return Graphics
     */
    protected function getGraphics(): Graphics
    {
        return new Graphics($this->getGraphicsProcessor(), new CustomList());
    }

    /**
     * @throws ImagesException
     * @return Graphics\Processor
     */
    protected function getGraphicsProcessor(): Graphics\Processor
    {
        return new Graphics\Processor(new Format\Factory());
    }
}
