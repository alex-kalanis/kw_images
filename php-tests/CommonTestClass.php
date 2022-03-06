<?php

use kalanis\kw_images\Graphics;
use kalanis\kw_images\ImagesException;
use kalanis\kw_paths\Extras\ExtendDir;
use PHPUnit\Framework\TestCase;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
class CommonTestClass extends TestCase
{
    const TEST_STRING = 'plokmijnuhbzgvtfcrdxesywaq3620951847';

    protected function extDir(): ExtendDir
    {
        return new ExtendDir($this->targetPath() . DIRECTORY_SEPARATOR, 'dsc', 'info', '.txt', 'tmb');
    }

    protected function targetPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'data';
    }
}


class XGraphics extends Graphics
{
    public function load(string $path): parent
    {
        throw new ImagesException('mock test');
    }
}
