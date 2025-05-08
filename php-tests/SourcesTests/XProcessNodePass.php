<?php

namespace tests\SourcesTests;


use kalanis\kw_files\Processing\Storage;


class XProcessNodePass extends Storage\ProcessNode
{
    public function isFile(array $entry): bool
    {
        return true;
    }
}
