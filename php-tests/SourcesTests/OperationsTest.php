<?php

namespace SourcesTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Sources;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class OperationsTest extends CommonTestClass
{
    /**
     * @throws FilesException
     */
    public function testCopyPass(): void
    {
        $lib = $this->getFilesLib();
        $src = ['testimage.png'];
        $tgt = ['testtree', 'tstimg.png'];

        $lib->xSet($src, static::TEST_STRING);
        // okay
        $this->assertTrue($lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        // already exists
        $this->expectExceptionMessage('tgtAlEx');
        $this->expectException(FilesException::class);
        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testCopyNotExists(): void
    {
        $lib = $this->getFilesLib();
        $src = ['testimate.png']; // not exists
        $tgt = ['testtree', 'tstimg.png'];

        $this->expectExceptionMessage('tgtNtEx');
        $this->expectException(FilesException::class);
        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testCopyOverPass(): void
    {
        $lib = $this->getFilesLib();

        $src = ['testimage.png'];
        $tgt = ['testtree', 'tstimg.png'];

        $lib->xSet($src, static::TEST_STRING);

        $this->assertTrue($lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->assertTrue($lib->xDataCopy($src, $tgt, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
    }

    /**
     * @throws FilesException
     */
    public function testCopyOverCleanupFail(): void
    {
        $lib = $this->getFilesFailCleanupLib();

        $src = ['testimage.png'];
        $tgt = ['testtree', 'tstimg.png'];

        $lib->xSet($src, static::TEST_STRING);

        $this->assertTrue($lib->xDataCopy($src, $tgt, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->expectExceptionMessage('unEx');
        $this->expectException(FilesException::class);
        $lib->xDataCopy($src, ['shall_end'], true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testCopyOverActionFail(): void
    {
        $lib = $this->getFilesFailActionLib();

        $src = ['testimage.png'];
        $tgt = ['testtree', 'tstimg.png'];

        $lib->xSet($src, static::TEST_STRING);

        $this->assertTrue($lib->xDataCopy($src, $tgt, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->expectExceptionMessage('cpEx');
        $this->expectException(FilesException::class);
        $lib->xDataCopy($src, ['shall_end'], true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testMovePass(): void
    {
        $lib = $this->getFilesLib();

        $src = ['testimage.png'];
        $tgt1 = ['testtree', 'tstimg1.png'];
        $tgt2 = ['testtree', 'tstimg2.png'];

        $lib->xSet($src, static::TEST_STRING);
        // now data
        $this->assertTrue($lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->assertTrue($lib->xDataRename($tgt1, $tgt2, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        // already exists
        $this->assertTrue($lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->expectExceptionMessage('tgtAlEx');
        $this->expectException(FilesException::class);
        $lib->xDataRename($tgt1, $tgt2, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testMoveNotExists(): void
    {
        $lib = $this->getFilesLib();

        $src = ['testimate.png']; // not exists
        $tgt = ['testtree', 'tstimg1.png'];

        $this->expectExceptionMessage('tgtNtEx');
        $this->expectException(FilesException::class);
        $lib->xDataRename($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testMoveOverPass(): void
    {
        $lib = $this->getFilesLib();

        $src = ['testimage.png'];
        $tgt1 = ['testtree', 'tstimg1.png'];
        $tgt2 = ['testtree', 'tstimg2.png'];

        $lib->xSet($src, static::TEST_STRING);

        // now data
        $this->assertTrue($lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->assertTrue($lib->xDataRename($tgt1, $tgt2, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        // overwrite
        $this->assertTrue($lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->assertTrue($lib->xDataRename($tgt1, $tgt2, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
    }

    /**
     * @throws FilesException
     */
    public function testMoveOverCleanupFail(): void
    {
        $lib = $this->getFilesFailCleanupLib();

        $src = ['testimage.png'];
        $tgt = ['testtree', 'tstimg.png'];

        $lib->xSet($src, static::TEST_STRING);

        $this->assertTrue($lib->xDataCopy($src, $tgt, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->expectExceptionMessage('unEx');
        $this->expectException(FilesException::class);
        $lib->xDataRename($src, [], true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testMoveOverActionFail(): void
    {
        $lib = $this->getFilesFailActionLib();

        $src = ['testimage.png'];
        $tgt = ['testtree', 'tstimg.png'];

        $lib->xSet($src, static::TEST_STRING);

        $this->assertTrue($lib->xDataCopy($src, $tgt, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        $this->expectExceptionMessage('cpEx');
        $this->expectException(FilesException::class);
        $lib->xDataRename($src, [], true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws FilesException
     */
    public function testRemove(): void
    {
        $lib = $this->getFilesLib();

        $src = ['testimage.png'];
        $tgt = ['testtree', 'tstimg.png'];

        $lib->xSet($src, static::TEST_STRING);
        // now data
        $this->assertTrue($lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx'));
        // already exists
        $this->assertTrue($lib->xDataRemove($tgt, 'tgtNtEx'));
        // not exists
        $this->assertTrue($lib->xDataRemove($tgt, 'tgtNtEx'));
    }

    /**
     * @throws FilesException
     */
    public function testRemoveCleanupFail(): void
    {
        $lib = $this->getFilesFailCleanupLib();

        $this->expectExceptionMessage('unEx');
        $this->expectException(FilesException::class);
        $lib->xDataRemove([], 'unEx');
    }

    protected function getFilesLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new XSourcesFiles(
            new Storage\ProcessNode($storage),
            new Storage\ProcessFile($storage),
            (new Config())->setData($params)
        );
    }

    protected function getFilesFailCleanupLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new XSourcesFiles(
            new XProcessNodePass($storage),
            new XProcessFileCleanupFail($storage),
            (new Config())->setData($params)
        );
    }

    protected function getFilesFailActionLib(array $params = [])
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        return new XSourcesFiles(
            new XProcessNodePass($storage),
            new XProcessFileActionFail($storage),
            (new Config())->setData($params)
        );
    }
}


/**
 * Class XFiles
 * Intentionally call protected methods, can test exceptions
 * @package SourcesTests
 */
class XSourcesFiles extends Sources\AFiles
{
    /**
     * For store work copy of desired file
     * @param string[] $path
     * @param mixed $content
     * @throws FilesException
     * @return bool
     */
    public function xSet(array $path, $content): bool
    {
        return $this->libFile->saveFile($this->getPath($path), $content);
    }

    public function getPath(array $path): array
    {
        return $path;
    }

    /**
     * @param string[] $source
     * @param string[] $target
     * @param bool $overwrite
     * @param string $sourceFileNotExistsErr
     * @param string $targetFileExistsErr
     * @param string $unlinkErr
     * @param string $copyErr
     * @throws FilesException
     * @return bool
     */
    public function xDataCopy(
        array $source, array $target, bool $overwrite, string $sourceFileNotExistsErr, string $targetFileExistsErr, string $unlinkErr, string $copyErr
    ): bool
    {
        return $this->dataCopy($source, $target, $overwrite, $sourceFileNotExistsErr, $targetFileExistsErr, $unlinkErr, $copyErr);
    }

    /**
     * @param string[] $source
     * @param string[] $target
     * @param bool $overwrite
     * @param string $sourceFileNotExistsErr
     * @param string $targetFileExistsErr
     * @param string $unlinkErr
     * @param string $copyErr
     * @throws FilesException
     * @return bool
     */
    public function xDataRename(
        array $source, array $target, bool $overwrite, string $sourceFileNotExistsErr, string $targetFileExistsErr, string $unlinkErr, string $copyErr
    ): bool
    {
        return $this->dataRename($source, $target, $overwrite, $sourceFileNotExistsErr, $targetFileExistsErr, $unlinkErr, $copyErr);
    }

    /**
     * @param string[] $source
     * @param string $unlinkErrDesc
     * @throws FilesException
     * @return bool
     */
    public function xDataRemove(array $source, string $unlinkErrDesc): bool
    {
        return $this->dataRemove($source, $unlinkErrDesc);
    }
}


class XProcessNodePass extends Storage\ProcessNode
{
    public function isFile(array $entry): bool
    {
        return true;
    }
}


class XProcessFileActionFail extends Storage\ProcessFile
{
    public function copyFile(array $source, array $dest): bool
    {
        $last = end($dest);
        $do = empty($last) || ('shall_end' === $last);
        return $do ? false : parent::copyFile($source, $dest);
    }

    public function moveFile(array $source, array $dest): bool
    {
        $last = end($dest);
        $die = empty($last) || ('shall_end' === $last);
        return $die ? false : parent::moveFile($source, $dest);
    }
}


class XProcessFileCleanupFail extends Storage\ProcessFile
{
    public function deleteFile(array $entry): bool
    {
        $last = end($entry);
        $die = empty($last) || ('shall_end' === $last);
        return $die ? false : parent::deleteFile($entry);
    }
}
