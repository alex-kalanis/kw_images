<?php

namespace FilesTests;


use CommonTestClass;
use kalanis\kw_images\Sources\Image;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\MimeType;
use kalanis\kw_paths\PathsException;


class ImageTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'testimage.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'testimage.png.txt';
        $tgt10 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $tgt11 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'tstimg.png';
        $tgt12 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'tstimg.png.txt';
        if (is_file($tgt0)) {
            unlink($tgt0);
        }
        if (is_file($tgt1)) {
            unlink($tgt1);
        }
        if (is_file($tgt2)) {
            unlink($tgt2);
        }
        if (is_file($tgt10)) {
            unlink($tgt10);
        }
        if (is_file($tgt11)) {
            unlink($tgt11);
        }
        if (is_file($tgt12)) {
            unlink($tgt12);
        }
    }

    /**
     * @throws ImagesException
     */
    public function testCheck(): void
    {
        $lib = $this->getLib();
        $lib->check('testimage.png');
        $this->assertNotNull($lib->getCreated('testimage.png', 'Y-m-d'));
    }

    /**
     * @throws ImagesException
     */
    public function testFailedCheck1(): void
    {
        $lib = $this->getLib();
        $this->expectException(ImagesException::class);
        $lib->check('not-a-image.txt');
    }

    /**
     * @throws ImagesException
     */
    public function testFailedCheck2(): void
    {
        $lib = $this->getLib([
            'max_size' => 512,
        ]);
        $this->expectException(ImagesException::class);
        $lib->check('testimage.png');
    }

    /**
     * @throws ImagesException
     * @throws PathsException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib([
            'width' => 512,
        ]);
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $lib->copy('testimage.png', '', 'testtree');
        $lib->processUploaded('testtree' . DIRECTORY_SEPARATOR .  'testimage.png');

        $lib->check('testtree' . DIRECTORY_SEPARATOR .  'testimage.png');
        $this->assertTrue($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->move('testimage.png', 'testtree', 'testtree' . DIRECTORY_SEPARATOR . 'tmb');
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertTrue($lib->isHere('testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->rename('testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR, 'testimage.png', 'tstimg.png');
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertTrue($lib->isHere('testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'tstimg.png'));

        $lib->delete('testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR, 'tstimg.png');
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'tstimg.png'));
    }

    /**
     * @param array
     * @return Image
     * @throws ImagesException
     */
    protected function getLib(array $params = [])
    {
        return new Image($this->extDir(), new Graphics(new Format\Factory(), new MimeType(true)), $params);
    }
}
