<?php

namespace kalanis\kw_images\Content;


use kalanis\kw_files\Extended\Processor;
use kalanis\kw_files\FilesException;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_images\Sources;
use kalanis\kw_images\Traits\TLang;
use kalanis\kw_mime\MimeException;
use kalanis\kw_paths\PathsException;


/**
 * Class Dirs
 * Create specific content
 * @package kalanis\kw_images\Content
 */
class Dirs
{
    use TLang;

    protected ImageSize $libSizes;
    protected Sources\Thumb $libThumb;
    protected Sources\DirDesc $libDirDesc;
    protected Sources\DirThumb $libDirThumb;
    protected Processor $libExt;

    public function __construct(ImageSize $sizes, Sources\Thumb $thumb, Sources\DirDesc $dirDesc, Sources\DirThumb $dirThumb, Processor $ext, ?IIMTranslations $lang = null)
    {
        $this->setImLang($lang);
        $this->libThumb = $thumb;
        $this->libDirDesc = $dirDesc;
        $this->libDirThumb = $dirThumb;
        $this->libExt = $ext;
        $this->libSizes = $sizes;
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @throws PathsException
     * @return bool
     */
    public function exists(array $path): bool
    {
        return $this->libExt->dirExists($path);
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @throws PathsException
     * @return string
     */
    public function getDescription(array $path): string
    {
        return $this->libDirDesc->get($path);
    }

    /**
     * @param string[] $path
     * @param string $description
     * @throws FilesException
     * @throws PathsException
     * @return bool
     */
    public function updateDescription(array $path, string $description = ''): bool
    {
        if (!empty($description)) {
            return $this->libDirDesc->set($path, $description);
        } else {
            return $this->libDirDesc->remove($path);
        }
    }

    /**
     * @param string[] $path
     * @throws PathsException
     * @return string|resource
     */
    public function getThumb(array $path)
    {
        try {
            return $this->libDirThumb->get($path);
        } catch (FilesException $ex) {
            return '';
        }
    }

    /**
     * @param string[] $path
     * @param string $fromWhichFile
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @return bool
     */
    public function updateThumb(array $path, string $fromWhichFile): bool
    {
        return $this->libSizes->process(
            $this->libThumb->getPath(array_merge($path, [$fromWhichFile])),
            $this->libDirThumb->getPath($path)
        );
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @throws PathsException
     * @return bool
     */
    public function removeThumb(array $path): bool
    {
        if ($this->libDirThumb->isHere($path)) {
            if (!$this->libDirThumb->delete($path)) {
                throw new FilesException($this->getImLang()->imDirThumbCannotRemoveCurrent());
            }
        }
        return true;
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @throws PathsException
     * @return bool
     */
    public function canUse(array $path): bool
    {
        return $this->libExt->isExtended($path);
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @throws PathsException
     * @return bool
     */
    public function create(array $path): bool
    {
        return $this->libExt->createDir($path, true);
    }

    /**
     * @param string[] $path
     * @throws FilesException
     * @throws PathsException
     * @return bool
     */
    public function createExtra(array $path): bool
    {
        return $this->libExt->makeExtended($path);
    }
}
