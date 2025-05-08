<?php

namespace tests\SourcesTests;


use kalanis\kw_files\Processing\Storage;


class XProcessFileCleanupFail extends Storage\ProcessFile
{
    public function deleteFile(array $entry): bool
    {
        $last = end($entry);
        $die = empty($last) || ('shall_end' === $last);
        return $die ? false : parent::deleteFile($entry);
    }
}
