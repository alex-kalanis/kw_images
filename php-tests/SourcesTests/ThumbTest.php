<?php

namespace SourcesTests;


use CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Sources\Thumb;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


/**
 * Class ThumbTest
 * Stored into memory, process there
 * @package SourcesTests
 */
class ThumbTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws PathsException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib();
        $this->assertFalse($lib->isHere(['testtree', 'testimage.png']));
        $this->assertTrue($lib->set(['testtree', 'testimage.png'], static::TEST_STRING));
        $this->assertTrue($lib->isHere(['testtree', 'testimage.png']));
        $this->assertEquals(static::TEST_STRING, $lib->get(['testtree', 'testimage.png']));

        $this->assertTrue($lib->copy('testimage.png', ['testtree'], ['dumptree']));
        $this->assertTrue($lib->move('testimage.png', ['testtree'], ['dumptree'] ,true));
        $this->assertFalse($lib->isHere(['testtree', 'testimage.png']));
        $this->assertTrue($lib->isHere(['dumptree', 'testimage.png']));

        $this->assertTrue($lib->rename(['dumptree'], 'testimage.png', 'tstimg.png'));
        $this->assertFalse($lib->isHere(['dumptree', 'testimage.png']));
        $this->assertTrue($lib->isHere(['dumptree', 'tstimg.png']));

        $this->assertTrue($lib->delete(['dumptree'], 'tstimg.png'));
        $this->assertFalse($lib->isHere(['dumptree', 'tstimg.png']));
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws PathsException
     * @return Thumb
     */
    protected function getLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new Thumb((new Factory())->getClass($storage), (new Config())->setData($params));
    }
}
