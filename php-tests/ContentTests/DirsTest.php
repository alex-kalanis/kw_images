<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\Extended\Processor;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Content\Dirs;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\MimeType;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class DirsTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws ImagesException
     */
    public function testDescription(): void
    {
        $tgt = ['testtree'];
        $lib = $this->getLib();

        $this->assertFalse($lib->exists($tgt));
        $this->assertEmpty($lib->getDescription($tgt));
        $this->assertTrue($lib->updateDescription($tgt, static::TEST_STRING));
        $this->assertEquals(static::TEST_STRING, $lib->getDescription($tgt));
        $this->assertTrue($lib->updateDescription($tgt));
        $this->assertEmpty($lib->getDescription($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     */
    public function testExtend1(): void
    {
        $tgt = ['testtree'];
        $lib = $this->getLib();

        $this->assertFalse($lib->exists($tgt));
        $this->assertFalse($lib->canUse($tgt));
        $this->assertTrue($lib->create($tgt));
        $this->assertTrue($lib->exists($tgt));
        $this->assertTrue($lib->canUse($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     */
    public function testExtend2(): void
    {
        $tgt = ['testtree'];
        $lib = $this->getLib();

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
     * @param array<string, string|int> $params
     * @throws ImagesException
     * @return XDirs
     */
    protected function getLib(array $params = []): XDirs
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $nodes = new Storage\ProcessNode($storage);
        $files = new Storage\ProcessFile($storage);
        $dirs = new Storage\ProcessDir($storage);
        $config = (new Config())->setData($params);

        return new XDirs(
            new ImageSize(
                new Graphics($this->getGraphicsProcessor(), new MimeType(true)),
                (new Graphics\ImageConfig())->setData($params),
                new Sources\Image($nodes, $files, $config)
            ),
            new Sources\Thumb($nodes, $files, $config),
            new Sources\DirDesc($nodes, $files, $config),
            new Sources\DirThumb($nodes, $files, $config),
            new Processor($dirs, $nodes, $config)
        );
    }

    /**
     * @param array<string, string|int> $params
     * @throws ImagesException
     * @return XDirs
     */
    protected function getLibThumbFail(array $params = []): XDirs
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $nodes = new Storage\ProcessNode($storage);
        $files = new Storage\ProcessFile($storage);
        $dirs = new Storage\ProcessDir($storage);
        $config = (new Config())->setData($params);

        return new XDirs(
            new ImageSize(
                new Graphics($this->getGraphicsProcessor(), new MimeType(true)),
                (new Graphics\ImageConfig())->setData($params),
                new Sources\Image($nodes, $files, $config)
            ),
            new Sources\Thumb($nodes, $files, $config),
            new Sources\DirDesc($nodes, $files, $config),
            new XSourceDirThumbFail($nodes, $files, $config),
            new Processor($dirs, $nodes, $config)
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


class XDirs extends Dirs
{
    public function getLibSizes(): ImageSize
    {
        return $this->libSizes;
    }

    public function getLibThumb(): Sources\DirThumb
    {
        return $this->libDirThumb;
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @return bool
     */
    public function createSimple(array $path): bool
    {
        return $this->libExt->createDir($path, false);
    }
}


class XSourceDirThumbFail extends Sources\DirThumb
{
    public function delete(array $whichDir): bool
    {
        return false;
    }
}
