<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Content\BasicOperations;
use kalanis\kw_images\Sources;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class BasicOperationsTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws PathsException
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
     * @return XBasicOperations
     */
    protected function getLib(array $params = []): XBasicOperations
    {
        $config = (new Config())->setData($params);
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
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
     * @return XBasicOperations
     */
    protected function getFailThumbLib(array $params = []): XBasicOperations
    {
        $config = (new Config())->setData($params);
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
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
     * @return XBasicOperations
     */
    protected function getFailDescLib(array $params = []): XBasicOperations
    {
        $config = (new Config())->setData($params);
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new XBasicOperations(
            new Sources\Image($access, $config),
            new Sources\Thumb($access, $config),
            new XSourceDescDie($access, $config)
        );
    }
}


class XBasicOperations extends BasicOperations
{
    public function getLibImage(): Sources\Image
    {
        return $this->libImage;
    }

    public function getLibThumb(): Sources\Thumb
    {
        return $this->libThumb;
    }

    public function getLibDesc(): Sources\Desc
    {
        return $this->libDesc;
    }
}


class XSourceDescDie extends Sources\Desc
{
    public function copy(string $fileName, array $sourceDir, array $targetDir, bool $overwrite = false): bool
    {
        throw new FilesException('mock desc copy fail');
    }

    public function move(string $fileName, array $sourceDir, array $targetDir, bool $overwrite = false): bool
    {
        throw new FilesException('mock desc move fail');
    }

    public function rename(array $path, string $sourceName, string $targetName, bool $overwrite = false): bool
    {
        throw new FilesException('mock desc rename fail');
    }
}


class XSourceThumbDie extends Sources\Thumb
{
    public function copy(string $fileName, array $sourceDir, array $targetDir, bool $overwrite = false): bool
    {
        throw new FilesException('mock thumb copy fail');
    }

    public function move(string $fileName, array $sourceDir, array $targetDir, bool $overwrite = false): bool
    {
        throw new FilesException('mock thumb move fail');
    }

    public function rename(array $path, string $sourceName, string $targetName, bool $overwrite = false): bool
    {
        throw new FilesException('mock thumb rename fail');
    }
}
