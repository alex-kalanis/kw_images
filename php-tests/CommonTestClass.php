<?php

use kalanis\kw_files\Interfaces\IProcessNodes;
use kalanis\kw_storage\StorageException;
use kalanis\kw_storage\Storage\Target;
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

    /**
     * @throws StorageException
     * @return Target\Memory
     */
    protected function getMemoryStructure(): Target\Memory
    {
        $memory = new Target\Memory();
        $memory->save('', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'dumptree', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . '.tmb', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . '.txt', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'targettree', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'targettree' . DIRECTORY_SEPARATOR . '.tmb', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'targettree' . DIRECTORY_SEPARATOR . '.txt', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testdir', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testdir' . DIRECTORY_SEPARATOR . '.tmb', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testdir' . DIRECTORY_SEPARATOR . '.txt', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'ext', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . '.tmb', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . '.txt', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'textfile.txt', 'dummy file');
        return $memory;
    }

    /**
     * @throws StorageException
     * @return Target\Memory
     */
    protected function getMemoryStructureNoFill(): Target\Memory
    {
        $memory = new Target\Memory();
        $memory->save('', IProcessNodes::STORAGE_NODE_KEY);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree', IProcessNodes::STORAGE_NODE_KEY);
        return $memory;
    }

    /**
     * @throws StorageException
     * @return Target\Memory
     */
    protected function getMemoryStructureNoDir(): Target\Memory
    {
        $memory = new Target\Memory();
        $memory->save('', IProcessNodes::STORAGE_NODE_KEY);
        return $memory;
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
