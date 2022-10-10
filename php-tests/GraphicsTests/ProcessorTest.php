<?php

namespace GraphicsTests;


use CommonTestClass;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\MimeType;


class ProcessorTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        if (is_file($tgt0)) {
            unlink($tgt0);
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
     */
    public function testResampleFull(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg.png';
        copy($src, $tgt0); // directly
        $conf = new Graphics\ImageConfig();
        $conf->setData(['max_width' => 120, 'max_height' => 80,]);
        $lib = $this->getGraphics();
        $lib->setSizes($conf);
        $lib->resize($tgt0, $tgt0);
        $this->assertTrue(file_exists($tgt0));

        $lib2 = $this->getGraphicsProcessor();
        $lib2->load('png', $tgt0);
        $this->assertEquals(106, $lib2->width());
        $this->assertEquals(80, $lib2->height());
        $this->assertEquals('', $conf->getTempPrefix());
    }

    /**
     * @throws ImagesException
     */
    public function testResizeNoLibs(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $this->expectExceptionMessage('Sizes to compare are not set.');
        $this->expectException(ImagesException::class);
        $lib->resize($src, $src);
    }

    /**
     * @throws ImagesException
     */
    public function testResizeBadType(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'textfile.txt';
        $lib = $this->getGraphics();
        $conf = new Graphics\ImageConfig();
        $conf->setData(['max_size' => 120000000, 'tmp_pref' => 'fghjkl']);
        $lib->setSizes($conf);
        $this->expectExceptionMessage('Wrong file mime type - got *text/plain*');
        $this->expectException(ImagesException::class);
        $lib->resize($src, $src);
    }

    /**
     * @throws ImagesException
     */
    public function testCheckPass(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $conf = new Graphics\ImageConfig();
        $conf->setData(['max_size' => 120000000, 'tmp_pref' => 'fghjkl']);
        $lib = $this->getGraphics();
        $lib->setSizes($conf);
        $this->assertTrue($lib->check($src));
        $this->assertEquals('fghjkl', $conf->getTempPrefix());
    }

    /**
     * @throws ImagesException
     */
    public function testCheckFailSizeCheck(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $this->expectExceptionMessage('Sizes to compare are not set');
        $this->expectException(ImagesException::class);
        $lib->check($src);
    }

    /**
     * @throws ImagesException
     */
    public function testCheckFailSize(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $lib->setSizes((new Graphics\ImageConfig())->setData(['max_size' => 120, ]));
        $this->expectExceptionMessage('This image is too large to use.');
        $this->expectException(ImagesException::class);
        $lib->check($src);
    }

    /**
     * @throws ImagesException
     */
    public function testCheckFailImage(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'not-a-image.txt';
        $lib = $this->getGraphics();
        $lib->setSizes((new Graphics\ImageConfig())->setData(['max_size' => 120, ]));
        $this->expectExceptionMessage('Cannot read file size. Exists?');
        $this->expectException(ImagesException::class);
        $lib->check($src);
    }

    /**
     * @throws ImagesException
     * @return Graphics
     */
    protected function getGraphics()
    {
        return new Graphics($this->getGraphicsProcessor(), new MimeType(true));
    }

    /**
     * @throws ImagesException
     * @return Graphics\Processor
     */
    protected function getGraphicsProcessor()
    {
        return new Graphics\Processor(new Format\Factory());
    }
}
