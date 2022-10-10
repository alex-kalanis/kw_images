<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_files\Processing\Storage;
use kalanis\kw_images\Content\Images;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\MimeType;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;


class ImagesTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws ImagesException
     */
    public function testDescription(): void
    {
        $tgt = ['testtree', 'testimage.png'];
        $lib = $this->getLib();

        $this->assertTrue($lib->updateDescription($tgt, static::TEST_STRING));
        $this->assertTrue($lib->updateDescription($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     */
    public function testUpdateThumb(): void
    {
        $src = ['testtree', 'testimage.png'];

        $params = [];
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $nodes = new Storage\ProcessNode($storage);
        $files = new Storage\ProcessFile($storage);
        $config = (new Config())->setData($params);

        $images = new Sources\Image($nodes, $files, $config);
        $thumbs = new Sources\Thumb($nodes, $files, $config);
        $lib = new Images(
            new ImageSize(
                new Graphics(new Graphics\Processor(new Format\Factory()), new MimeType(true)),
                (new Graphics\ImageConfig())->setData($params),
                $images
            ),
            $thumbs,
            new Sources\Desc($nodes, $files, $config)
        );

        $this->assertTrue($images->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png'))));
        $this->assertFalse($thumbs->isHere($src));
        $this->assertTrue($lib->updateThumb($src));
        $this->assertTrue($thumbs->isHere($src));
        $this->assertTrue($lib->removeThumb($src));
        $this->assertFalse($thumbs->isHere($src));
    }

    /**
     * @param array<string, string|int> $params
     * @param Sources\Image|null $image
     * @throws ImagesException
     * @return Images
     */
    protected function getLib(array $params = []): Images
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), new Target\Memory());
        $nodes = new Storage\ProcessNode($storage);
        $files = new Storage\ProcessFile($storage);
        $config = (new Config())->setData($params);

        return new Images(
            new ImageSize(
                new Graphics(new Graphics\Processor(new Format\Factory()), new MimeType(true)),
                (new Graphics\ImageConfig())->setData($params),
                new Sources\Image($nodes, $files, $config)
            ),
            new Sources\Thumb($nodes, $files, $config),
            new Sources\Desc($nodes, $files, $config)
        );
    }
}
