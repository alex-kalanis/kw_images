<?php

namespace tests\ContentTests;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\Sources;


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
