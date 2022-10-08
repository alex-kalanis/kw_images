<?php

namespace SourcesTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Sources\DirDesc;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class DirDescTest extends CommonTestClass
{
    /**
     * @throws FilesException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib();
        $this->assertFalse($lib->canUse(['testtree']));
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
     * @param array<string, string|int> $params
     * @return DirDesc
     */
    protected function getLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new DirDesc(
            new Storage\ProcessNode($storage),
            new Storage\ProcessFile($storage),
            (new Config())->setData($params)
        );

    }
}
