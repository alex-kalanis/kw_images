<?php

namespace tests\SourcesTests;


use tests\CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Sources\DirDesc;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\StorageException;


class DirDescTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib();
        $this->assertTrue($lib->canUse(['testtree'])); // has structure
        $this->assertFalse($lib->isHere(['testtree']));
        $this->assertEmpty($lib->get(['testtree']));

        $lib->set(['testtree'], static::TEST_STRING);
        $this->assertTrue($lib->isHere(['testtree']));
        $this->assertEquals(static::TEST_STRING, $lib->get(['testtree']));

        $lib->remove(['testtree']);
        $this->assertFalse($lib->isHere(['testtree']));
        $this->assertEmpty($lib->get(['testtree']));

        $this->expectException(FilesException::class);
        $lib->get(['testtree'], true);
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testProcessing2(): void
    {
        $lib = $this->getEmptyLib();
        $this->assertFalse($lib->canUse(['testtree'])); // has no structure
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     * @return DirDesc
     */
    protected function getLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        return new DirDesc((new Factory())->getClass($storage), (new Config())->setData($params));
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     * @return DirDesc
     */
    protected function getEmptyLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructureNoFill());
        return new DirDesc((new Factory())->getClass($storage), (new Config())->setData($params));
    }
}
