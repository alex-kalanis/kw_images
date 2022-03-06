<?php

namespace FilesTests;


use CommonTestClass;
use kalanis\kw_images\Files\DirDesc;
use kalanis\kw_images\ImagesException;


class DirDescTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'info.txt';
        $tgt12 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'info.txt';
        $tgt22 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'info.txt';
        if (is_file($tgt2)) {
            unlink($tgt2);
        }
        if (is_file($tgt12)) {
            unlink($tgt12);
        }
        if (is_file($tgt22)) {
            unlink($tgt22);
        }
    }

    /**
     * @throws ImagesException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib();
        $this->assertTrue($lib->canUse('testtree'));
        $this->assertFalse($lib->isHere('testtree'));
        $this->assertEmpty($lib->get('testtree'));

        $lib->set('testtree', static::TEST_STRING);
        $this->assertTrue($lib->isHere('testtree'));
        $this->assertEquals(static::TEST_STRING, $lib->get('testtree'));

        $lib->remove('testtree');
        $this->assertFalse($lib->isHere('testtree'));
        $this->assertEmpty($lib->get('testtree'));
    }

    /**
     * @throws ImagesException
     */
    public function testFailSave(): void
    {
        $lib = $this->getLib();
        $this->assertFalse($lib->canUse('testimage.png'));
        $this->expectException(ImagesException::class);
        $lib->set('testimage.png', static::TEST_STRING); // non-existent directory
    }

    /**
     * @return DirDesc
     */
    protected function getLib()
    {
        return new DirDesc($this->extDir());
    }
}
