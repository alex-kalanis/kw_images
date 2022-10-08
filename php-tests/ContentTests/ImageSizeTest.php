<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\MimeType;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class ImageSizeTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws ImagesException
     */
    public function testUpdatePass(): void
    {
        $src = ['testtree', 'testimage.png'];
        $tgt = ['testtree', 'tstimg1.png'];
        $lib = $this->getLib();

        $this->assertTrue($lib->getImage()->set($src, @file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png')));
        $this->assertTrue($lib->process($src, $tgt));

        // just check libraries calls
        $this->assertNotEmpty($lib->getImage());
    }

    /**
     * @throws FilesException
     * @throws ImagesException
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
     * @return ImageSize
     *@throws ImagesException
     */
    protected function getLib(array $params = []): ImageSize
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $nodes = new Storage\ProcessNode($storage);
        $files = new Storage\ProcessFile($storage);
        $config = (new Config())->setData($params);

        return new ImageSize(
            new Graphics($this->getGraphicsProcessor(), new MimeType(true)),
            (new Graphics\ImageConfig())->setData($params),
            new Sources\Image($nodes, $files, $config)
        );
    }

    /**
     * @param array<string, string|int> $params
     * @return ImageSize
     *@throws ImagesException
     */
    protected function getLibImageFail(array $params = []): ImageSize
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $nodes = new Storage\ProcessNode($storage);
        $files = new Storage\ProcessFile($storage);
        $config = (new Config())->setData($params);

        return new ImageSize(
            new Graphics($this->getGraphicsProcessor(), new MimeType(true)),
            (new Graphics\ImageConfig())->setData($params),
            new XSourceImageFail($nodes, $files, $config)
        );
    }

    /**
     * @return Graphics\Processor
     * @throws ImagesException
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
