<?php

namespace tests\SourcesTests;


use tests\CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Sources\Desc;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\StorageException;


class DescTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib();
        $this->assertFalse($lib->isHere(['testtree', 'testimage.png']));
        $this->assertFalse($lib->isHere(['dumptree', 'tstimg.png']));
        $this->assertEmpty($lib->get(['testtree', 'testimage.png']));
        $this->assertEmpty($lib->get(['dumptree', 'tstimg.png']));

        $lib->set(['testtree', 'testimage.png'], static::TEST_STRING);
        $this->assertTrue($lib->isHere(['testtree', 'testimage.png']));
        $this->assertEquals(static::TEST_STRING, $lib->get(['testtree', 'testimage.png']));

        $this->assertTrue($lib->copy('testimage.png', ['testtree'], ['dumptree']));
        $this->assertEquals(static::TEST_STRING, $lib->get(['dumptree', 'testimage.png']));

        $this->assertTrue($lib->move('testimage.png', ['testtree'], ['dumptree'] ,true));
        $this->assertFalse($lib->isHere(['testtree', 'testimage.png']));
        $this->assertEmpty($lib->get(['testtree', 'testimage.png']));

        $this->assertTrue($lib->rename(['dumptree'], 'testimage.png', 'tstimg.png'));
        $this->assertEmpty($lib->get(['dumptree', 'testimage.png']));
        $this->assertTrue($lib->isHere(['dumptree', 'tstimg.png']));
        $this->assertEquals(static::TEST_STRING, $lib->get(['dumptree', 'tstimg.png']));

        $this->assertTrue($lib->delete(['dumptree'], 'tstimg.png'));
        $this->assertFalse($lib->isHere(['testtree', 'testimage.png']));
        $this->assertFalse($lib->isHere(['dumptree', 'tstimg.png']));
        $this->assertEmpty($lib->get(['testtree', 'testimage.png']));
        $this->assertEmpty($lib->get(['dumptree', 'testimage.png']));
        $this->assertEmpty($lib->get(['dumptree', 'tstimg.png']));

        $this->expectException(FilesException::class);
        $lib->get(['dumptree', 'tstimg.png'], true);
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     * @return Desc
     */
    protected function getLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        return new Desc((new Factory())->getClass($storage), (new Config())->setData($params));
    }
}
