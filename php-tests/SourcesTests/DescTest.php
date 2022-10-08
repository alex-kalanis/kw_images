<?php

namespace SourcesTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Sources\Desc;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class DescTest extends CommonTestClass
{
    /**
     * @throws FilesException
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
     * @return Desc
     */
    protected function getLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new Desc(
            new Storage\ProcessNode($storage),
            new Storage\ProcessFile($storage),
            (new Config())->setData($params)
        );
    }
}
