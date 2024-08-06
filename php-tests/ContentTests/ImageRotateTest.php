<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Configs;
use kalanis\kw_images\Content\ImageRotate;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\Check\CustomList;
use kalanis\kw_mime\MimeException;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\StorageException;


/**
 * @requires function imagerotate
 * @requires function imageflip
 */
class ImageRotateTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     * @requires function imagecreatefromjpeg
     * @requires function imagejpeg
     * @requires function imagerotate
     * @requires function imageflip
     */
    public function testUpdatePass(): void
    {
        $src = ['testtree', 'testimage5.jpg'];
        $tgt = ['testtree', 'tstimg5.jpg'];
        $lib = $this->getLib();

        $this->assertTrue($lib->getImage()->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage5.jpg'))));
        $this->assertTrue($lib->process($src, 270, null, $tgt));

        // just check libraries calls
        $this->assertNotEmpty($lib->getImage());
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     */
    public function testUpdateFailNoResource(): void
    {
        $src = ['testtree', 'testimage.png'];
        $tgt = ['testtree', 'tstimg1.png'];
        $lib = $this->getLibImageFail();

        $this->expectExceptionMessage('Cannot get base image.');
        $this->expectException(FilesException::class);
        $lib->process($src, null, null, $tgt);
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     */
    public function testUpdateFailBadResource(): void
    {
        $src = ['testtree', 'textfile.txt'];
        $tgt = ['testtree', 'tstimg1.png'];
        $lib = $this->getLib();

        $this->expectExceptionMessage('Wrong file mime type - got *text/plain*');
        $this->expectException(ImagesException::class);
        $lib->process($src, 0, null, $tgt);
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     * @return ImageRotate
     */
    protected function getLib(array $params = []): ImageRotate
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new ImageRotate(
            new Graphics($this->getGraphicsProcessor(), new CustomList()),
            (new Configs\ImageConfig())->setData($params),
            new Sources\Image($access, $config)
        );
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     * @return ImageRotate
     */
    protected function getLibImageFail(array $params = []): ImageRotate
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new ImageRotate(
            new Graphics($this->getGraphicsProcessor(), new CustomList()),
            (new Configs\ImageConfig())->setData($params),
            new XSourceImageRotateFail($access, $config)
        );
    }

    /**
     * @throws ImagesException
     * @return Graphics\Processor
     */
    protected function getGraphicsProcessor(): Graphics\Processor
    {
        return new Graphics\Processor(new Format\Factory());
    }
}


class XSourceImageRotateFail extends Sources\Image
{
    public function get(array $path)
    {
        return '';
    }
}
