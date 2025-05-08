<?php

namespace tests\ContentTests;


use kalanis\kw_images\Sources;


class XSourceDirThumbFail extends Sources\DirThumb
{
    public function delete(array $whichDir): bool
    {
        return false;
    }
}
