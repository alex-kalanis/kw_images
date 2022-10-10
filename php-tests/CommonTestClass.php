<?php

use PHPUnit\Framework\TestCase;


/**
 * Class CommonTestClass
 * The structure for mocking and configuration seems so complicated, but it's necessary to let it be totally idiot-proof
 */
class CommonTestClass extends TestCase
{
    const TEST_STRING = 'plokmijnuhbzgvtfcrdxesywaq3620951847';

    protected function targetPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'data';
    }
}


if (!class_exists('GdImage')) {
    class GdImage
    {
        // just typehint for php7- where the Gd passes as resource, not inside the class as in php8+
    }
}
