<?php

namespace kalanis\kw_images;


use kalanis\kw_images\Interfaces\IIMTranslations;


/**
 * Class Translations
 * @package kalanis\kw_images
 */
class Translations implements IIMTranslations
{
    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imGdLibNotPresent(): string
    {
        return 'GD2 library is not present!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imImageMagicLibNotPresent(): string
    {
        return 'ImageMagic not installed or too old!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imCannotCreateFromResource(): string
    {
        return 'Cannot create image from resource!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imCannotSaveResource(): string
    {
        return 'Cannot save image resource!';
    }

    public function imUnknownType(string $type): string
    {
        return sprintf('Unknown type *%s*', $type);
    }

    public function imWrongMime(string $mime): string
    {
        return sprintf('Wrong file mime type - got *%s*', $mime);
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imImageCannotResize(): string
    {
        return 'Image cannot be resized!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imImageCannotResample(): string
    {
        return 'Image cannot be resampled!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imImageCannotCreateEmpty(): string
    {
        return 'Cannot create empty image!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imImageCannotGetSize(): string
    {
        return 'Cannot get image size!';
    }

    public function imImageLoadFirst(): string
    {
        return 'You must load image first!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imDescCannotRead(): string
    {
        return 'Cannot read description';
    }

    public function imDescCannotAdd(): string
    {
        return 'Cannot add description';
    }

    public function imDescCannotRemove(): string
    {
        return 'Cannot remove description!';
    }

    public function imDescCannotFind(): string
    {
        return 'Cannot find that description.';
    }

    public function imDescAlreadyExistsHere(): string
    {
        return 'Description with the same name already exists here.';
    }

    public function imDescCannotRemoveOld(): string
    {
        return 'Cannot remove old description.';
    }

    public function imDescCannotCopyBase(): string
    {
        return 'Cannot copy base description.';
    }

    public function imDescCannotMoveBase(): string
    {
        return 'Cannot move base description.';
    }

    public function imDescCannotRenameBase(): string
    {
        return 'Cannot rename base description.';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imDirDescCannotRead(): string
    {
        return 'Cannot read dir desc!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imDirDescCannotAdd(): string
    {
        return 'Cannot write dir desc!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imDirDescCannotRemove(): string
    {
        return 'Cannot remove dir desc!';
    }

    public function imDirDescCannotAccess(): string
    {
        return 'Cannot access that file!';
    }

    public function imDirThumbCannotRemove(): string
    {
        return 'Cannot remove dir thumb!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imDirThumbCannotRemoveCurrent(): string
    {
        return 'Cannot remove current thumb!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imDirThumbCannotRemoveOld(): string
    {
        return 'Cannot remove old thumb!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imDirThumbCannotRestore(): string
    {
        return 'Cannot restore current thumb back!';
    }

    public function imImageSizeExists(): string
    {
        return 'Cannot read file size. Exists?';
    }

    public function imImageSizeTooLarge(): string
    {
        return 'This image is too large to use.';
    }

    public function imImageCannotFind(): string
    {
        return 'Cannot find that image.';
    }

    public function imImageCannotRemove(): string
    {
        return 'Cannot remove image.';
    }

    public function imImageAlreadyExistsHere(): string
    {
        return 'Image with the same name already exists here.';
    }

    public function imImageCannotRemoveOld(): string
    {
        return 'Cannot remove old image.';
    }

    public function imImageCannotCopyBase(): string
    {
        return 'Cannot copy base image.';
    }

    public function imImageCannotMoveBase(): string
    {
        return 'Cannot move base image.';
    }

    public function imImageCannotRenameBase(): string
    {
        return 'Cannot rename base image.';
    }

    public function imThumbCannotFind(): string
    {
        return 'Cannot find that thumb.';
    }

    public function imThumbCannotRemove(): string
    {
        return 'Cannot remove thumb!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imThumbCannotRemoveCurrent(): string
    {
        return 'Cannot remove current thumb!';
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function imThumbCannotRestore(): string
    {
        return 'Cannot remove current thumb back!';
    }

    public function imThumbAlreadyExistsHere(): string
    {
        return 'Thumb with the same name already exists here.';
    }

    public function imThumbCannotRemoveOld(): string
    {
        return 'Cannot remove old thumb.';
    }

    public function imThumbCannotCopyBase(): string
    {
        return 'Cannot copy base thumb.';
    }

    public function imThumbCannotMoveBase(): string
    {
        return 'Cannot move base thumb.';
    }

    public function imThumbCannotRenameBase(): string
    {
        return 'Cannot rename base thumb.';
    }
}
