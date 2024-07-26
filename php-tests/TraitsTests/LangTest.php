<?php

namespace TraitsTests;


use CommonTestClass;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_images\Traits\TLang;
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


class XLang
{
    use TLang;
}


class XTrans implements IIMTranslations
{
    public function imGdLibNotPresent(): string
    {
        return 'mock';
    }

    public function imRotateLibNotPresent(): string
    {
        return 'mock';
    }

    public function imImageMagicLibNotPresent(): string
    {
        return 'mock';
    }

    public function imCannotCreateFromResource(): string
    {
        return 'mock';
    }

    public function imCannotSaveResource(): string
    {
        return 'mock';
    }

    public function imUnknownMime(): string
    {
        return 'mock';
    }

    public function imUnknownType(string $mime): string
    {
        return 'mock';
    }

    public function imWrongInstance(string $class): string
    {
        return 'mock';
    }

    public function imWrongMime(string $mime): string
    {
        return 'mock';
    }

    public function imImageCannotResize(): string
    {
        return 'mock';
    }

    public function imSizesNotSet(): string
    {
        return 'mock';
    }

    public function imImageCannotResample(): string
    {
        return 'mock';
    }

    public function imImageCannotOrientate(): string
    {
        return 'mock';
    }

    public function imImageCannotCreateEmpty(): string
    {
        return 'mock';
    }

    public function imImageCannotGetSize(): string
    {
        return 'mock';
    }

    public function imImageLoadFirst(): string
    {
        return 'mock';
    }

    public function imDescCannotRemove(): string
    {
        return 'mock';
    }

    public function imDescCannotFind(): string
    {
        return 'mock';
    }

    public function imDescAlreadyExistsHere(): string
    {
        return 'mock';
    }

    public function imDescCannotRemoveOld(): string
    {
        return 'mock';
    }

    public function imDescCannotCopyBase(): string
    {
        return 'mock';
    }

    public function imDescCannotMoveBase(): string
    {
        return 'mock';
    }

    public function imDescCannotRenameBase(): string
    {
        return 'mock';
    }

    public function imDirThumbCannotRemove(): string
    {
        return 'mock';
    }

    public function imDirThumbCannotRemoveCurrent(): string
    {
        return 'mock';
    }

    public function imImageSizeExists(): string
    {
        return 'mock';
    }

    public function imImageSizeTooLarge(): string
    {
        return 'mock';
    }

    public function imImageCannotFind(): string
    {
        return 'mock';
    }

    public function imImageCannotRemove(): string
    {
        return 'mock';
    }

    public function imImageAlreadyExistsHere(): string
    {
        return 'mock';
    }

    public function imImageCannotRemoveOld(): string
    {
        return 'mock';
    }

    public function imImageCannotCopyBase(): string
    {
        return 'mock';
    }

    public function imImageCannotMoveBase(): string
    {
        return 'mock';
    }

    public function imImageCannotRenameBase(): string
    {
        return 'mock';
    }

    public function imThumbCannotFind(): string
    {
        return 'mock';
    }

    public function imThumbCannotRemove(): string
    {
        return 'mock';
    }

    public function imThumbAlreadyExistsHere(): string
    {
        return 'mock';
    }

    public function imThumbCannotRemoveOld(): string
    {
        return 'mock';
    }

    public function imThumbCannotGetBaseImage(): string
    {
        return 'mock';
    }

    public function imThumbCannotStoreTemporaryImage(): string
    {
        return 'mock';
    }

    public function imThumbCannotLoadTemporaryImage(): string
    {
        return 'mock';
    }

    public function imThumbCannotCopyBase(): string
    {
        return 'mock';
    }

    public function imThumbCannotMoveBase(): string
    {
        return 'mock';
    }

    public function imThumbCannotRenameBase(): string
    {
        return 'mock';
    }
}