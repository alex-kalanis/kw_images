<?php

namespace tests\ContentTests;


use tests\CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Sources;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\StorageException;


class BasicOperationsTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testCopyPass(): void
    {
        $lib = $this->getLib();

        $src = ['testtree', 'testimage.png'];
        $tgt = ['targettree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));
        $this->assertFalse($lib->getLibImage()->isHere($tgt));
        $this->assertFalse($lib->getLibDesc()->isHere($tgt));
        $this->assertFalse($lib->getLibThumb()->isHere($tgt));

        // action!
        $this->assertTrue($lib->copy($src, ['targettree']));

        // check result
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));
        $this->assertTrue($lib->getLibImage()->isHere($tgt));
        $this->assertTrue($lib->getLibDesc()->isHere($tgt));
        $this->assertTrue($lib->getLibThumb()->isHere($tgt));
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testCopyFailThumb(): void
    {
        $lib = $this->getFailThumbLib();

        $src = ['testtree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));

        // action!
        $this->expectExceptionMessage('mock thumb copy fail');
        $this->expectException(FilesException::class);
        $lib->copy($src, ['targettree']);
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testCopyFailDesc(): void
    {
        $lib = $this->getFailDescLib();

        $src = ['testtree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));

        // action!
        $this->expectExceptionMessage('mock desc copy fail');
        $this->expectException(FilesException::class);
        $lib->copy($src, ['targettree']);
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testMovePass(): void
    {
        $lib = $this->getLib();

        $src = ['testtree', 'testimage.png'];
        $tgt = ['targettree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));
        $this->assertFalse($lib->getLibImage()->isHere($tgt));
        $this->assertFalse($lib->getLibDesc()->isHere($tgt));
        $this->assertFalse($lib->getLibThumb()->isHere($tgt));

        // action!
        $this->assertTrue($lib->move($src, ['targettree']));

        // check result
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));
        $this->assertTrue($lib->getLibImage()->isHere($tgt));
        $this->assertTrue($lib->getLibDesc()->isHere($tgt));
        $this->assertTrue($lib->getLibThumb()->isHere($tgt));
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testMoveFailThumb(): void
    {
        $lib = $this->getFailThumbLib();

        $src = ['testtree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));

        // action!
        $this->expectExceptionMessage('mock thumb move fail');
        $this->expectException(FilesException::class);
        $lib->move($src, ['targettree']);
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testMoveFailDesc(): void
    {
        $lib = $this->getFailDescLib();

        $src = ['testtree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));

        // action!
        $this->expectExceptionMessage('mock desc move fail');
        $this->expectException(FilesException::class);
        $lib->move($src, ['targettree']);
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testRenamePass(): void
    {
        $lib = $this->getLib();

        $src = ['testtree', 'testimage.png'];
        $tgt = ['testtree', 'tstimg1.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));
        $this->assertFalse($lib->getLibImage()->isHere($tgt));
        $this->assertFalse($lib->getLibDesc()->isHere($tgt));
        $this->assertFalse($lib->getLibThumb()->isHere($tgt));

        // action!
        $this->assertTrue($lib->rename($src, 'tstimg1.png'));

        // check result
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));
        $this->assertTrue($lib->getLibImage()->isHere($tgt));
        $this->assertTrue($lib->getLibDesc()->isHere($tgt));
        $this->assertTrue($lib->getLibThumb()->isHere($tgt));
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testRenameFailThumb(): void
    {
        $lib = $this->getFailThumbLib();

        $src = ['testtree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));

        // action!
        $this->expectExceptionMessage('mock thumb rename fail');
        $this->expectException(FilesException::class);
        $lib->rename($src, 'tstimg1.png');
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testRenameFailDesc(): void
    {
        $lib = $this->getFailDescLib();

        $src = ['testtree', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));

        // action!
        $this->expectExceptionMessage('mock desc rename fail');
        $this->expectException(FilesException::class);
        $lib->rename($src, 'tstimg1.png');
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testDelete(): void
    {
        $lib = $this->getLib();

        $src = ['testdir', 'testimage.png'];

        // check
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));

        // set fot test
        $this->assertTrue($lib->getLibImage()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibDesc()->set($src, static::TEST_STRING));
        $this->assertTrue($lib->getLibThumb()->set($src, static::TEST_STRING));

        // check again
        $this->assertTrue($lib->getLibImage()->isHere($src));
        $this->assertTrue($lib->getLibDesc()->isHere($src));
        $this->assertTrue($lib->getLibThumb()->isHere($src));

        // action!
        $this->assertTrue($lib->delete($src));

        // check empty
        $this->assertFalse($lib->getLibImage()->isHere($src));
        $this->assertFalse($lib->getLibDesc()->isHere($src));
        $this->assertFalse($lib->getLibThumb()->isHere($src));
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     * @return XBasicOperations
     */
    protected function getLib(array $params = []): XBasicOperations
    {
        $config = (new Config())->setData($params);
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new XBasicOperations(
            new Sources\Image($access, $config),
            new Sources\Thumb($access, $config),
            new Sources\Desc($access, $config)
        );
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     * @return XBasicOperations
     */
    protected function getFailThumbLib(array $params = []): XBasicOperations
    {
        $config = (new Config())->setData($params);
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new XBasicOperations(
            new Sources\Image($access, $config),
            new XSourceThumbDie($access, $config),
            new Sources\Desc($access, $config)
        );
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws PathsException
     * @throws StorageException
     * @return XBasicOperations
     */
    protected function getFailDescLib(array $params = []): XBasicOperations
    {
        $config = (new Config())->setData($params);
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new XBasicOperations(
            new Sources\Image($access, $config),
            new Sources\Thumb($access, $config),
            new XSourceDescDie($access, $config)
        );
    }
}
