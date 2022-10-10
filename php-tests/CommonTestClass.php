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


if (!function_exists('imagecreatefromavif')) {
    /**
     * @param string $filename
     * @throws ImagickException
     * @return GdImage|resource|false
     */
    function imagecreatefromavif($filename)
    {
        throw new \ImagickException('bad version');
    }
}


if (!function_exists('imageavif')) {
    /**
     * @param GdImage|resource $image
     * @param string|null $to
     * @throws ImagickException
     * @return bool
     */
    function imageavif($image, $to = null): bool
    {
        throw new \ImagickException('bad version');
    }
}
