<?php

namespace SourcesTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Sources\Image;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class ImageTest extends CommonTestClass
{
    /**
     * @throws FilesException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib();
        $tst1 = ['testtree', 'testimage.png'];

        $this->assertFalse($lib->isHere($tst1));
        $this->assertTrue($lib->set($tst1, static::TEST_STRING));
        $this->assertEquals(static::TEST_STRING, $lib->get($tst1));
        $this->assertEmpty($lib->getCreated($tst1));

        $this->assertEquals('testimage_0.png', $lib->findFreeName(['testtree'], 'testimage', '.png'));

        $this->assertTrue($lib->copy('testimage.png', ['testtree'], ['testtree', 'ext']));
        $this->assertTrue($lib->delete(['testtree', 'ext'], 'testimage.png'));

        $this->assertTrue($lib->move('testimage.png', ['testtree'], ['testtree', 'tmb']));
        $this->assertFalse($lib->isHere(['testtree', 'testimage.png']));
        $this->assertTrue($lib->isHere(['testtree', 'tmb', 'testimage.png']));

        $this->assertTrue($lib->rename(['testtree', 'tmb'], 'testimage.png', 'tstimg.png'));
        $this->assertFalse($lib->isHere(['testtree', 'tmb', 'testimage.png']));
        $this->assertTrue($lib->isHere(['testtree', 'tmb', 'tstimg.png']));

        $this->assertTrue($lib->delete(['testtree', 'tmb'], 'tstimg.png'));
        $this->assertFalse($lib->isHere(['testtree', 'tmb', 'tstimg.png']));
    }

    protected function getLib(): Image
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new Image(
            new Storage\ProcessNode($storage),
            new Storage\ProcessFile($storage),
            new Config()
        );
    }
}
