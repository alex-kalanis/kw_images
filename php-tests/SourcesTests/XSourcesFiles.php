<?php

namespace tests\SourcesTests;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\Sources;
use kalanis\kw_paths\PathsException;


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
     * @throws PathsException
     * @return bool
     */
    public function xSet(array $path, $content): bool
    {
        return $this->lib->saveFile($this->getPath($path), $content);
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
     * @throws PathsException
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
     * @throws PathsException
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
     * @throws PathsException
     * @return bool
     */
    public function xDataRemove(array $source, string $unlinkErrDesc): bool
    {
        return $this->dataRemove($source, $unlinkErrDesc);
    }
}
