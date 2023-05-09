<?php

namespace ContentTests;


use CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Content\Images;
use kalanis\kw_images\Content\ImageSize;
use kalanis\kw_images\Content\ImageUpload;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Sources;
use kalanis\kw_mime\Check\CustomList;
use kalanis\kw_mime\MimeException;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage\Key;
use kalanis\kw_storage\Storage\Target;
use kalanis\kw_storage\StorageException;


class ImageUploadTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg1.png';
        if (is_file($tgt0)) {
            unlink($tgt0);
        }
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     */
    public function testUploadPass(): void
    {
        $src = $this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png';
        $file = $this->targetPath() . DIRECTORY_SEPARATOR . 'tstimg1.png';
        $tgt = ['testtree', 'tstimg1.png'];
        $lib = $this->getLib();

        copy($src, $file);

        $this->assertTrue($lib->process($tgt, $file, static::TEST_STRING, true, true));
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws StorageException
     * @throws PathsException
     */
    public function testFreeName(): void
    {
        $memory = new Target\Memory();
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png' , static::TEST_STRING);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png' , static::TEST_STRING);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg_0.png' , static::TEST_STRING);
        $memory->save(DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg_1.png' , static::TEST_STRING);

        $lib = $this->getLib([], $memory);
        $this->assertEquals('solo.png', $lib->findFreeName(['testtree'], 'solo.png'));
        $this->assertEquals('testimage_0.png', $lib->findFreeName(['testtree'], 'testimage.png'));
        $this->assertEquals('tstimg_2.png', $lib->findFreeName(['testtree'], 'tstimg.png'));
        $this->assertEquals('tstimg.png', $lib->findFreeName(['testtree', 'testdir'], 'tstimg.png'));
    }

    /**
     * @param array<string, string|int> $params
     * @param Target\Memory|null $memory
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     * @return ImageUpload
     */
    protected function getLib(array $params = [], ?Target\Memory $memory = null): ImageUpload
    {
        $storage = new \kalanis\kw_storage\Storage\Storage(new Key\DefaultKey(), $memory ?? new Target\Memory());
        $config = (new Config())->setData($params);
        $composite = new Factory();
        $access = $composite->getClass($storage);
        $graphics = new Graphics(new Graphics\Processor(new Graphics\Format\Factory()), new CustomList());
        $image = new Sources\Image($access, $config);
        return new ImageUpload(  // process uploaded images
            $graphics,
            $image,
            (new Graphics\ImageConfig())->setData($params),
            new Images(
                new ImageSize(
                    $graphics,
                    (new Graphics\ThumbConfig())->setData($params),
                    $image
                ),
                new Sources\Image($access, $config),
                new Sources\Thumb($access, $config),
                new Sources\Desc($access, $config),
            )
        );
    }
}
