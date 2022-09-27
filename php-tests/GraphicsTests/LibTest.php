<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\MimeType;


class LibTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg1.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg2.png';
        if (is_file($tgt0)) {
            unlink($tgt0);
        }
        if (is_file($tgt1)) {
            unlink($tgt1);
        }
        if (is_file($tgt2)) {
            unlink($tgt2);
        }
    }

    /**
     * @throws ImagesException
     */
    public function testSimpleOne(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
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
        $this->expectException(ImagesException::class);
        $lib->load('png', $src);
    }

    /**
     * @throws ImagesException
     */
    public function testResize(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
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
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
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
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        copy($src, $tgt0); // directly
        $lib = $this->getGraphics();
        $lib->setSizes((new Graphics\ImageConfig())->setData(['max_width' => 120, 'max_height' => 80,]));
        $lib->resize($tgt0, $tgt0);
        $this->assertTrue(file_exists($tgt0));

        $lib2 = $this->getGraphicsProcessor();
        $lib2->load('png', $tgt0);
        $this->assertEquals(120, $lib2->width());
        $this->assertEquals(80, $lib2->height());
    }

    /**
     * @throws ImagesException
     */
    public function testCheckPass(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $lib->setSizes((new Graphics\ImageConfig())->setData(['max_size' => 120000000,]));
        $this->assertTrue($lib->check($src));
    }

    /**
     * @throws ImagesException
     */
    public function testCheckFailSize(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $lib = $this->getGraphics();
        $lib->setSizes((new Graphics\ImageConfig())->setData(['max_size' => 120, ]));
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
        $this->expectException(ImagesException::class);
        $lib->check($src);
    }

    /**
     * @return Graphics
     * @throws ImagesException
     */
    protected function getGraphics()
    {
        return new Graphics($this->getGraphicsProcessor(), new MimeType(true));
    }

    /**
     * @return Graphics\Processor
     * @throws ImagesException
     */
    protected function getGraphicsProcessor()
    {
        return new Graphics\Processor(new Format\Factory());
    }
}
