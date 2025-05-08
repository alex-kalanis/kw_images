<?php

namespace tests\SourcesTests;


use kalanis\kw_files\Processing\Storage;


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
