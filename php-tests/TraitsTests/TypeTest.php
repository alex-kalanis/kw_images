<?php

namespace tests\TraitsTests;


use tests\CommonTestClass;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\Check\CustomList;
use kalanis\kw_mime\MimeException;


class TypeTest extends CommonTestClass
{
    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testSimple(): void
    {
        $lib = new XType();
        $lib->initType(new CustomList());
        $this->assertEquals('jpeg', $lib->xGetType(['something', 'somewhere', 'file.jpg']));
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testWrongMime(): void
    {
        $lib = new XType();
        $lib->initType(new CustomList());
        $this->expectException(ImagesException::class);
        $lib->xGetType(['something', 'somewhere', 'file.php']);
    }

    /**
     * @throws ImagesException
     * @throws MimeException
     */
    public function testMimeFail(): void
    {
        $lib = new XType();
        $this->expectException(ImagesException::class);
        $lib->xGetType(['nothing will be checked - no checker set']);
    }
}
