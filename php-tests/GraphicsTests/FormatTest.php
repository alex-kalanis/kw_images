<?php

namespace BasicTests;


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
        $tgt6 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimgx.png';
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
    }

    /**
     * @param string $type
     * @param bool $fails
     * @throws ImagesException
     * @dataProvider factoryProvider
     */
    public function testInitFactory(string $type, bool $fails): void
    {
        $lib = new Format\Factory();
        if (!$fails) {
            $this->assertInstanceOf('\kalanis\kw_images\Graphics\Format\AFormat', $lib->getByType($type, new Translations()));
        } else {
            $this->expectException(ImagesException::class);
            $lib->getByType($type, new Translations());
        }
    }

    public function factoryProvider(): array
    {
        return [
            ['png', false],
            ['bmp', false],
            ['jpeg', false],
            ['txt', true],
        ];
    }

    /**
     * @throws ImagesException
     */
    public function testContentPng(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $this->contentTesting(new Format\Png(), $tgt0);
    }

    /**
     * @throws ImagesException
     * Slow test, I know about it
     */
    public function testContentBmp(): void
    {
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.bmp';
        $this->contentTesting(new Format\Bmp(), $tgt1);
    }

    /**
     * @throws ImagesException
     */
    public function testContentGif(): void
    {
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.gif';
        $this->contentTesting(new Format\Gif(), $tgt2);
    }

    /**
     * @throws ImagesException
     */
    public function testContentJpg(): void
    {
        $tgt3 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.jpg';
        $this->contentTesting(new Format\Jpeg(), $tgt3);
    }

    /**
     * @throws ImagesException
     */
    public function testContentWbmp(): void
    {
        $tgt4 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.wbmp';
        $this->contentTesting(new Format\Wbmp(), $tgt4);
    }

    /**
     * @throws ImagesException
     */
    public function testContentWebp(): void
    {
        $tgt5 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.webp';
        $this->contentTesting(new Format\Webp(), $tgt5);
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
