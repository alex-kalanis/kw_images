<?php

namespace tests\ContentTests;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\Content\Dirs;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Sources;
use kalanis\kw_paths\PathsException;


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
     * @throws PathsException
     * @return bool
     */
    public function createSimple(array $path): bool
    {
        return $this->libExt->createDir($path, false);
    }
}
