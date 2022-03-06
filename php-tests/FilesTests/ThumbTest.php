<?php

namespace FilesTests;


use CommonTestClass;
use kalanis\kw_images\Files\Thumb;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\MimeType;
use kalanis\kw_paths\PathsException;


class ThumbTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'testimage.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'testimage.png.txt';
        $tgt10 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt11 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'testimage.png';
        $tgt12 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'testimage.png.txt';
        $tgt20 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $tgt21 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'tstimg.png';
        $tgt22 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'tstimg.png.txt';
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
        if (is_file($tgt20)) {
            unlink($tgt20);
        }
        if (is_file($tgt21)) {
            unlink($tgt21);
        }
        if (is_file($tgt22)) {
            unlink($tgt22);
        }
    }

    /**
     * @throws ImagesException
     * @throws PathsException
     */
    public function testProcessing(): void
    {
        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib = $this->getLib();
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->create('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'); // full source here, later it will be separated paths and files
        $this->assertTrue($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->copy('testimage.png', 'testtree', 'dumptree');
        $lib->move('testimage.png', 'testtree', 'dumptree' ,true);
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertTrue($lib->isHere('dumptree' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->rename('dumptree', 'testimage.png', 'tstimg.png');
        $this->assertFalse($lib->isHere('dumptree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertTrue($lib->isHere('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));

        $lib->delete('dumptree', 'tstimg.png');
        $this->assertFalse($lib->isHere('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));
    }

    /**
     * @throws ImagesException
     * @throws PathsException
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
     * @return Thumb
     * @throws ImagesException
     */
    protected function getLib(array $params = [])
    {
        return new Thumb($this->extDir(), new Graphics(new Format\Factory(), new MimeType(true)), $params);
    }

    /**
     * @return Thumb
     * @throws ImagesException
     */
    protected function getFailLib()
    {
        return new Thumb($this->extDir(), new \XGraphics(new Format\Factory(), new MimeType(true)));
    }
}
