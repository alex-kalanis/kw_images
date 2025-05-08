<?php

namespace tests\ContentTests;


use tests\CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Configs;
use kalanis\kw_images\Content\ImageRotate;
use kalanis\kw_images\Content\Images;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\Graphics\Format;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\Check\CustomList;
use kalanis\kw_mime\MimeException;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\StorageException;


class ImagesTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testImage(): void
    {
        $tgt = ['testtree', 'testimage.png'];
        $lib = $this->getLib();

        $this->assertEquals($tgt, $lib->reversePath($tgt));
        $this->assertFalse($lib->exists($tgt));
        $this->assertEmpty($lib->get($tgt));
        $this->assertTrue($lib->set($tgt, static::TEST_STRING));
        $this->assertTrue($lib->exists($tgt));
        $this->assertEquals(static::TEST_STRING, $lib->get($tgt));
        $this->assertTrue($lib->remove($tgt));
        $this->assertFalse($lib->exists($tgt));
        $this->assertEmpty($lib->get($tgt));
        $this->assertEmpty($lib->created($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     */
    public function testDescription(): void
    {
        $tgt = ['testtree', 'testimage.png'];
        $lib = $this->getLib();

        $this->assertEquals(['testtree', '.txt', 'testimage.png.dsc'], $lib->reverseDescriptionPath($tgt));
        $this->assertEmpty($lib->getDescription($tgt));
        $this->assertTrue($lib->updateDescription($tgt, static::TEST_STRING));
        $this->assertEquals(static::TEST_STRING, $lib->getDescription($tgt));
        $this->assertTrue($lib->updateDescription($tgt));
        $this->assertEmpty($lib->getDescription($tgt));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     */
    public function testThumb(): void
    {
        $src = ['testtree', 'testimage.png'];

        $params = [];
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        $images = new Sources\Image($access, $config);
        $thumbs = new Sources\Thumb($access, $config);
        $graphics = new Graphics(new Graphics\Processor(new Format\Factory()), new CustomList());
        $lib = new Images(
            new ImageSize(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            new ImageRotate(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            $images,
            $thumbs,
            new Sources\Desc($access, $config)
        );

        $this->assertEquals(['testtree', '.tmb', 'testimage.png'], $lib->reverseThumbPath($src));

        $this->assertEmpty($lib->getThumb($src));
        $this->assertTrue($images->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png'))));
        $this->assertFalse($thumbs->isHere($src));
        $this->assertTrue($lib->updateThumb($src));
        $this->assertTrue($thumbs->isHere($src));
        $this->assertNotEmpty($lib->getThumb($src));
        $this->assertTrue($lib->removeThumb($src));
        $this->assertFalse($thumbs->isHere($src));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     * @requires function imagerotate
     * @requires function imageflip
     */
    public function testRotate(): void
    {
        $src = ['testtree', 'testimage.png'];

        $params = [];
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        $images = new Sources\Image($access, $config);
        $thumbs = new Sources\Thumb($access, $config);
        $graphics = new Graphics(new Graphics\Processor(new Format\Factory()), new CustomList());
        $lib = new Images(
            new ImageSize(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            new ImageRotate(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            $images,
            $thumbs,
            new Sources\Desc($access, $config)
        );

        $this->assertEmpty($lib->getThumb($src));
        $this->assertTrue($images->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png'))));
        $this->assertFalse($thumbs->isHere($src));
        $this->assertTrue($lib->rotate($src, 270));
        $this->assertFalse($thumbs->isHere($src));
        $this->assertTrue($lib->updateThumb($src));
        $this->assertTrue($thumbs->isHere($src));
        $this->assertTrue($lib->rotate($src, 270));
        $this->assertNotEmpty($lib->getThumb($src));
        $this->assertTrue($lib->removeThumb($src));
        $this->assertFalse($thumbs->isHere($src));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @throws StorageException
     * @requires function imagerotate
     * @requires function imageflip
     */
    public function testFlip(): void
    {
        $src = ['testtree', 'testimage.png'];

        $params = [];
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        $images = new Sources\Image($access, $config);
        $thumbs = new Sources\Thumb($access, $config);
        $graphics = new Graphics(new Graphics\Processor(new Format\Factory()), new CustomList());
        $lib = new Images(
            new ImageSize(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            new ImageRotate(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            $images,
            $thumbs,
            new Sources\Desc($access, $config)
        );

        $this->assertEmpty($lib->getThumb($src));
        $this->assertTrue($images->set($src, strval(@file_get_contents($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png'))));
        $this->assertFalse($thumbs->isHere($src));
        $this->assertTrue($lib->flip($src, IMG_FLIP_BOTH));
        $this->assertFalse($thumbs->isHere($src));
        $this->assertTrue($lib->updateThumb($src));
        $this->assertTrue($thumbs->isHere($src));
        $this->assertTrue($lib->flip($src, IMG_FLIP_BOTH));
        $this->assertNotEmpty($lib->getThumb($src));
        $this->assertTrue($lib->removeThumb($src));
        $this->assertFalse($thumbs->isHere($src));
    }

    /**
     * @param array<string, string|int> $params
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @throws StorageException
     * @return Images
     */
    protected function getLib(array $params = []): Images
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $this->getMemoryStructure());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);

        $images = new Sources\Image($access, $config);
        $graphics = new Graphics(new Graphics\Processor(new Format\Factory()), new CustomList());
        return new Images(
            new ImageSize(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            new ImageRotate(
                $graphics,
                (new Configs\ImageConfig())->setData($params),
                $images
            ),
            $images,
            new Sources\Thumb($access, $config),
            new Sources\Desc($access, $config)
        );
    }
}
