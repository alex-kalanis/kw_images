<?php

namespace FilesTests;


use CommonTestClass;
use kalanis\kw_images\Files\DirThumb;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\MimeType;


class DirThumbTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'info.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'info.jpg';
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
    public function testProcessing(): void
    {
        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib = $this->getLib([
            'tmb_ext' => '.jpg',
        ]);
        $this->assertTrue($lib->canUse('testtree'));
        $this->assertFalse($lib->isHere('testtree'));
        $lib->create('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'); // full source here, later it will be separated paths and files
        $this->assertTrue($lib->isHere('testtree'));
        $lib->delete('testtree');
        $this->assertFalse($lib->isHere('testtree'));
    }

    /**
     * @throws ImagesException
     */
    public function testFailProcessing(): void
    {
        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib = $this->getLib();
        $lib->create('testtree' . DIRECTORY_SEPARATOR .  'testimage.png');
        $libF = $this->getFailLib();
        $this->expectException(ImagesException::class);
        $libF->create('testtree' . DIRECTORY_SEPARATOR .  'testimage.png');
    }

    /**
     * @param array
     * @return DirThumb
     * @throws ImagesException
     */
    protected function getLib(array $params = [])
    {
        return new DirThumb($this->extDir(), new Graphics(new Format\Factory(), new MimeType(true)), $params);
    }

    /**
     * @return DirThumb
     * @throws ImagesException
     */
    protected function getFailLib()
    {
        return new DirThumb($this->extDir(), new \XGraphics(new Format\Factory(), new MimeType(true)));
    }
}
