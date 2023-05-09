<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\Check\CustomList;
use kalanis\kw_mime\MimeException;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class ImageSizeTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     */
    public function testUpdatePass(): void
    {
        $src = ['testtree', 'testimage.png'];
        $tgt = ['testtree', 'tstimg1.png'];
        $lib = $this->getLib();

        $this->assertTrue($lib->getImage()->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png'))));
        $this->assertTrue($lib->process($src, $tgt));

        // just check libraries calls
        $this->assertNotEmpty($lib->getImage());
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     */
    public function testUpdateFailNoResource(): void
    {
        $src = ['testtree', 'testimage.png'];
        $tgt = ['testtree', 'tstimg1.png'];
        $lib = $this->getLibImageFail();

        $this->expectExceptionMessage('Cannot get base image.');
        $this->expectException(FilesException::class);
        $lib->process($src, $tgt);
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @return ImageSize
     */
    protected function getLib(array $params = []): ImageSize
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new ImageSize(
            new Graphics($this->getGraphicsProcessor(), new CustomList()),
            (new Graphics\ImageConfig())->setData($params),
            new Sources\Image($access, $config)
        );
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @return ImageSize
     */
    protected function getLibImageFail(array $params = []): ImageSize
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        return new ImageSize(
            new Graphics($this->getGraphicsProcessor(), new CustomList()),
            (new Graphics\ImageConfig())->setData($params),
            new XSourceImageFail($access, $config)
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


class XSourceImageFail extends Sources\Image
{
    public function get(array $path)
    {
        return '';
    }
}
