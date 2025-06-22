<?php

if (!class_exists('GdImage')) {
    class GdImage
    {
        // just typehint for php7- where the Gd passes as resource, not inside the class as in php8+
    }
}


if (!function_exists('imagecreatefromavif')) {
    /**
     * @param string $filename
     * @throws RuntimeException
     * @return GdImage|resource|false
     */
    function imagecreatefromavif(/** @scrutinizer ignore-unused */ $filename)
    {
        throw new \RuntimeException('bad version');
    }
}


if (!function_exists('imageavif')) {
    /**
     * @param GdImage|resource $image
     * @param string|null $to
     * @throws RuntimeException
     * @return bool
     */
    function imageavif(/** @scrutinizer ignore-unused */ $image, /** @scrutinizer ignore-unused */ $to = null): bool
    {
        throw new \RuntimeException('bad version');
    }
}
