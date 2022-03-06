<?php

namespace FilesTests;


use CommonTestClass;
use kalanis\kw_images\Files\Desc;
use kalanis\kw_images\ImagesException;
use kalanis\kw_paths\PathsException;


class DescTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'testimage.png.txt';
        $tgt12 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'testimage.png.txt';
        $tgt22 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'tstimg.png.txt';
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
     * @throws PathsException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib();
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertFalse($lib->isHere('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));
        $this->assertEmpty($lib->get('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertEmpty($lib->get('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));

        $lib->set('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', static::TEST_STRING);
        $this->assertTrue($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertEquals(static::TEST_STRING, $lib->get('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->copy('testimage.png', 'testtree', 'dumptree');
        $this->assertEquals(static::TEST_STRING, $lib->get('dumptree' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->move('testimage.png', 'testtree', 'dumptree' ,true);
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertEmpty($lib->get('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));

        $lib->rename('dumptree', 'testimage.png', 'tstimg.png');
        $this->assertEmpty($lib->get('dumptree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertTrue($lib->isHere('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));
        $this->assertEquals(static::TEST_STRING, $lib->get('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));

        $lib->delete('dumptree', 'tstimg.png');
        $this->assertFalse($lib->isHere('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertFalse($lib->isHere('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));
        $this->assertEmpty($lib->get('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertEmpty($lib->get('dumptree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertEmpty($lib->get('dumptree' . DIRECTORY_SEPARATOR .  'tstimg.png'));
    }

    /**
     * @throws ImagesException
     */
    public function testFailSave(): void
    {
        $lib = $this->getLib();
        $this->expectException(ImagesException::class);
        $lib->set('testimage.png', static::TEST_STRING); // non-existent directory
    }

    /**
     * @return Desc
     */
    protected function getLib()
    {
        return new Desc($this->extDir());
    }
}
