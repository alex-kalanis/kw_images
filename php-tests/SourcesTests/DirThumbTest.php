<?php

namespace SourcesTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Sources\DirThumb;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class DirThumbTest extends CommonTestClass
{
    /**
     * @throws FilesException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib([
            'tmb_ext' => '.jpg',
        ]);
        $this->assertFalse($lib->isHere(['testtree']));
        $this->assertTrue($lib->set(['testtree'], static::TEST_STRING)); // full source here, later it will be separated paths and files
        $this->assertEquals(static::TEST_STRING, $lib->get(['testtree']));
        $this->assertTrue($lib->isHere(['testtree']));
        $this->assertTrue($lib->delete(['testtree']));
        $this->assertFalse($lib->isHere(['testtree']));
    }

    /**
     * @param array<string, string|int> $params
     * @return DirThumb
     */
    protected function getLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new DirThumb(
            new Storage\ProcessNode($storage),
            new Storage\ProcessFile($storage),
            (new Config())->setData($params)
        );
    }
}
