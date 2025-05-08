<?php

namespace tests\TraitsTests;


use tests\CommonTestClass;
use kalanis\kw_images\Translations;


class LangTest extends CommonTestClass
{
    public function testSimple(): void
    {
        $lib = new XLang();
        $this->assertNotEmpty($lib->getImLang());
        $this->assertInstanceOf(Translations::class, $lib->getImLang());
        $lib->setImLang(new XTrans());
        $this->assertInstanceOf(XTrans::class, $lib->getImLang());
        $lib->setImLang(null);
        $this->assertInstanceOf(Translations::class, $lib->getImLang());
    }
}
