<?php

namespace tests\AccessTests;


use tests\CommonTestClass;
use kalanis\kw_files\Access\Factory;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\Access;
use kalanis\kw_images\Content;
use kalanis\kw_images\ImagesException;
use kalanis\kw_paths\PathsException;
use kalanis\kw_storage\Storage;


class FactoryTest extends CommonTestClass
{
    /**
     * @throws FilesException
     * @throws PathsException
     */
    public function testOperations(): void
    {
        $data = $this->getLib()->getOperations();
        $this->assertNotEmpty($data);
        $this->assertInstanceOf(Content\BasicOperations::class, $data);
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     */
    public function testDirs(): void
    {
        $data = $this->getLib()->getDirs();
        $this->assertNotEmpty($data);
        $this->assertInstanceOf(Content\Dirs::class, $data);
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     */
    public function testImages(): void
    {
        $data = $this->getLib()->getImages();
        $this->assertNotEmpty($data);
        $this->assertInstanceOf(Content\Images::class, $data);
    }

    /**
     * @throws FilesException
     * @throws ImagesException
     * @throws PathsException
     */
    public function testUpload(): void
    {
        $data = $this->getLib()->getUpload();
        $this->assertNotEmpty($data);
        $this->assertInstanceOf(Content\ImageUpload::class, $data);
    }

    /**
     * @throws FilesException
     * @throws PathsException
     * @return Access\Factory
     */
    protected function getLib(): Access\Factory
    {
        return new Access\Factory((new Factory())->getClass(
            new Storage\Storage(new Storage\Key\DefaultKey(), new Storage\Target\Memory())
        ));
    }
}
