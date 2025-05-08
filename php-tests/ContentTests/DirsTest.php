<?php

namespace tests\ContentTests;


use tests\CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\Extended\Processor;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Configs;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\Check\CustomList;
use kalanis\kw_mime\MimeException;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;
use kalanis\kw_storage\StorageException;


class DirsTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testDescription(): void
    {
        $tgt = ['testtree'];
        $lib = $this->getLib();

        $this->assertTrue($lib->exists($tgt));
        $this->assertEmpty($lib->getDescription($tgt));
        $this->assertTrue($lib->updateDescription($tgt, static::TEST_STRING));
        $this->assertEquals(static::TEST_STRING, $lib->getDescription($tgt));
        $this->assertTrue($lib->updateDescription($tgt));
        $this->assertEmpty($lib->getDescription($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testExtend1(): void
    {
        $tgt = ['testtree'];
        $lib = $this->getLib($this->getMemoryStructureNoDir());

        $this->assertFalse($lib->exists($tgt));
        $this->assertFalse($lib->canUse($tgt));
        $this->assertTrue($lib->create($tgt));
        $this->assertTrue($lib->exists($tgt));
        $this->assertTrue($lib->canUse($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testExtend2(): void
    {
        $tgt = ['testtree'];
        $lib = $this->getLib($this->getMemoryStructureNoDir());

        $this->assertFalse($lib->exists($tgt));
        $this->assertFalse($lib->canUse($tgt));
        $this->assertTrue($lib->createSimple($tgt));
        $this->assertTrue($lib->exists($tgt));
        $this->assertFalse($lib->canUse($tgt));
        $this->assertTrue($lib->createExtra($tgt));
        $this->assertTrue($lib->canUse($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     */
    public function testUpdatePass(): void
    {
        $src = ['testtree', '.tmb', 'testimage.png'];
        $tgt = ['testtree'];
        $lib = $this->getLib();

        $this->assertTrue($lib->getLibSizes()->getImage()->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png'))));
        $this->assertFalse($lib->getLibThumb()->isHere($tgt));
        $this->assertEmpty($lib->getThumb($tgt));
        $this->assertTrue($lib->updateThumb($tgt, 'testimage.png'));
        $this->assertTrue($lib->getLibThumb()->isHere($tgt));
        $this->assertNotEmpty($lib->getThumb($tgt));
        $this->assertTrue($lib->removeThumb($tgt));
        $this->assertFalse($lib->getLibThumb()->isHere($tgt));
        $this->assertEmpty($lib->getThumb($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     */
    public function testRemoveFail(): void
    {
        $src = ['testtree', '.tmb', 'testimage.png'];
        $tgt = ['testtree'];
        $lib = $this->getLibThumbFail();

        $this->assertTrue($lib->getLibSizes()->getImage()->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png'))));
        $this->assertFalse($lib->getLibThumb()->isHere($tgt));
        $this->assertTrue($lib->updateThumb($tgt, 'testimage.png'));
        $this->assertTrue($lib->getLibThumb()->isHere($tgt));

        $this->expectExceptionMessage('Cannot remove current thumb!');
        $this->expectException(FilesException::class);
        $lib->removeThumb($tgt);
    }

    /**
     * @param Target\Memory|null $memory
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     * @return XDirs
     */
    protected function getLib(?Target\Memory $memory = null, array $params = []): XDirs
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $memory ?: $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new XDirs(
            new ImageSize(
                new Graphics($this->getGraphicsProcessor(), new CustomList()),
                (new Configs\ImageConfig())->setData($params),
                new Sources\Image($access, $config)
            ),
            new Sources\Thumb($access, $config),
            new Sources\DirDesc($access, $config),
            new Sources\DirThumb($access, $config),
            new Processor($access, $config)
        );
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     * @return XDirs
     */
    protected function getLibThumbFail(array $params = []): XDirs
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new XDirs(
            new ImageSize(
                new Graphics($this->getGraphicsProcessor(), new CustomList()),
                (new Configs\ImageConfig())->setData($params),
                new Sources\Image($access, $config)
            ),
            new Sources\Thumb($access, $config),
            new Sources\DirDesc($access, $config),
            new XSourceDirThumbFail($access, $config),
            new Processor($access, $config)
        );
    }

    /**
     * @throws ImagesException
     * @return Graphics\Processor
     */
    protected function getGraphicsProcessor(): Graphics\Processor
    {
        return new Graphics\Processor(new Format\Factory());
    }
}
