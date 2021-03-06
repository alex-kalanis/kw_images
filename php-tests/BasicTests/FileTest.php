<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_images\Files;
use kalanis\kw_images\ImagesException;
use kalanis\kw_paths\Extras\ExtendDir;
use kalanis\kw_paths\PathsException;


class FileTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg1.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg2.png';
        if (is_file($tgt0)) {
            unlink($tgt0);
        }
        if (is_file($tgt1)) {
            unlink($tgt1);
        }
        if (is_file($tgt2)) {
            unlink($tgt2);
        }
    }

    public function testExtendDir(): void
    {
        $lib = $this->getFilesLib();
        $this->assertNotEmpty($lib->getExtendDir());
    }

    /**
     * @throws PathsException
     */
    public function testWritable(): void
    {
        $lib = $this->getFilesLib();
        $lib->xCheckWritable(__DIR__);
        $this->expectException(PathsException::class);
        $lib->xCheckWritable(__DIR__ . DIRECTORY_SEPARATOR . 'not-a-file');
    }

    /**
     * @throws ImagesException
     */
    public function testCopy(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        // okay
        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        // already exists
        $this->expectException(ImagesException::class);
        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    public function testCopyCannot(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimate.png'; // not exists
        $tgt = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        // not a file
        $this->expectException(ImagesException::class);
        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    public function testCopyOver(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';

        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        $lib->xDataCopy($src, $tgt, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        $this->assertTrue(true);
    }

    public function testCopyNoDir(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt = $this->targetPath() . DIRECTORY_SEPARATOR . 'not-a-tree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        // not a file
        $this->expectException(ImagesException::class);
        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws ImagesException
     */
    public function testMove(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg1.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg2.png';
        // now data
        $lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        $lib->xDataRename($tgt1, $tgt2, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        // already exists
        $lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        $this->expectException(ImagesException::class);
        $lib->xDataRename($tgt1, $tgt2, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    public function testMoveCannot(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimate.png'; // not exists
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg1.png';
        // not a file
        $this->expectException(ImagesException::class);
        $lib->xDataRename($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    public function testMoveOver(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg1.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg2.png';

        // now data
        $lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        $lib->xDataRename($tgt1, $tgt2, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        // overwrite
        $lib->xDataCopy($src, $tgt1, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        $lib->xDataRename($tgt1, $tgt2, true, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');

        $this->assertTrue(true);
    }

    public function testMoveNoDir(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt = $this->targetPath() . DIRECTORY_SEPARATOR . 'not-a-tree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        // not a file
        $this->expectException(ImagesException::class);
        $lib->xDataRename($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
    }

    /**
     * @throws ImagesException
     */
    public function testRemove(): void
    {
        $lib = $this->getFilesLib();
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        // now data
        $lib->xDataCopy($src, $tgt, false, 'tgtNtEx', 'tgtAlEx', 'unEx', 'cpEx');
        // already exists
        $lib->xDataRemove($tgt, 'tgtNtEx');
        // not exists
        $lib->xDataRemove($tgt, 'tgtNtEx');

        $this->assertTrue(true);
    }

    protected function getFilesLib()
    {
        return new XFiles(new ExtendDir($this->targetPath()));
    }
}


class XFiles extends Files\AFiles
{
    public function getPath(string $path): string
    {
        return $path;
    }

    /**
     * @param string $path
     * @throws PathsException
     */
    public function xCheckWritable(string $path): void
    {
        $this->checkWritable($path);
    }

    /**
     * @param string $source
     * @param string $target
     * @param bool $overwrite
     * @param string $sourceFileNotExistsErr
     * @param string $targetFileExistsErr
     * @param string $unlinkErr
     * @param string $copyErr
     * @throws ImagesException
     */
    public function xDataCopy(
        string $source, string $target, bool $overwrite, string $sourceFileNotExistsErr, string $targetFileExistsErr, string $unlinkErr, string $copyErr
    ): void
    {
        $this->dataCopy($source, $target, $overwrite, $sourceFileNotExistsErr, $targetFileExistsErr, $unlinkErr, $copyErr);
    }

    /**
     * @param string $source
     * @param string $target
     * @param string $unlinkErrDesc
     * @param string $copyErrDesc
     * @throws ImagesException
     */
    public function xDataOverwriteCopy(string $source, string $target, string $unlinkErrDesc, string $copyErrDesc): void
    {
        $this->dataOverwriteCopy($source, $target, $unlinkErrDesc, $copyErrDesc);
    }

    /**
     * @param string $source
     * @param string $target
     * @param bool $overwrite
     * @param string $sourceFileNotExistsErr
     * @param string $targetFileExistsErr
     * @param string $unlinkErr
     * @param string $copyErr
     * @throws ImagesException
     */
    public function xDataRename(
        string $source, string $target, bool $overwrite, string $sourceFileNotExistsErr, string $targetFileExistsErr, string $unlinkErr, string $copyErr
    ): void
    {
        $this->dataRename($source, $target, $overwrite, $sourceFileNotExistsErr, $targetFileExistsErr, $unlinkErr, $copyErr);
    }

    /**
     * @param string $source
     * @param string $target
     * @param string $unlinkErrDesc
     * @param string $copyErrDesc
     * @throws ImagesException
     */
    public function xDataOverwriteRename(string $source, string $target, string $unlinkErrDesc, string $copyErrDesc): void
    {
        $this->dataOverwriteCopy($source, $target, $unlinkErrDesc, $copyErrDesc);
    }

    /**
     * @param string $source
     * @param string $unlinkErrDesc
     * @throws ImagesException
     */
    public function xDataRemove(string $source, string $unlinkErrDesc): void
    {
        $this->dataRemove($source, $unlinkErrDesc);
    }
}
