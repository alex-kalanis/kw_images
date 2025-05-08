<?php

namespace tests\ContentTests;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\Sources;


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
