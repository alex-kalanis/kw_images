<?php

namespace kalanis\kw_images\Content;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_images\Interfaces\ISizes;
use kalanis\kw_images\Sources;
use kalanis\kw_images\TLang;


/**
 * Class Processor
 * Process image from source
 * @package kalanis\kw_images\Content
 */
class Processor
{
    use TLang;

    /** @var Sources\Image */
    protected $libImage = null;
    /** @var Sources\Thumb */
    protected $libThumb = null;
    /** @var Graphics */
    protected $libGraphics = null;
    /** @var ISizes */
    protected $config = null;

    public function __construct(Graphics $graphics, ISizes $config, Sources\Image $image, Sources\Thumb $thumb, ?IIMTranslations $lang = null)
    {
        $this->setLang($lang);
        $this->libImage = $image;
        $this->libThumb = $thumb;
        $this->libGraphics = $graphics;
        $this->config = $config;
    }

    /**
     * @param string[] $sourcePath
     * @param string[] $targetPath
     * @throws FilesException
     * @throws ImagesException
     * @return bool
     */
    public function update(array $sourcePath, array $targetPath): bool
    {
        $sourceFull = array_values($sourcePath);
        $targetFull = array_values($targetPath);
        $sourceFile = array_pop($sourcePath);
        $targetFile = array_pop($targetPath);

        $tempRemoteFile = $this->randomName();
        $tempPath = tempnam(sys_get_temp_dir(), $this->config->getTempPrefix());

        try {
            // get from the storage
            $resource = $this->libImage->get($sourceFull);
            if (empty($resource)) {
                throw new FilesException($this->getLang()->imThumbCannotCopyBaseImage());
            }

            if (false === @file_put_contents($tempPath, $resource)) {
                throw new FilesException($this->getLang()->imThumbCannotStoreTemporaryImage());
            }

            // now process image locally
            $this->libGraphics->setSizes($this->config);
            $this->libGraphics->resize($tempPath, $sourceFile, $targetFile);

            // return result to the storage as new file
            $result = @file_get_contents($tempPath);
            if (false === $result) {
                throw new FilesException($this->getLang()->imThumbCannotLoadTemporaryImage());
            }

            $this->backupOld($sourcePath, $sourceFile, $tempRemoteFile);
            $this->libThumb->set($targetFull, $result);

        } catch (ImagesException $ex) {
            $this->restoreOld($sourcePath, $sourceFile, $tempRemoteFile);
            throw $ex;
        }
        $this->removeOld($sourcePath, $tempRemoteFile);
        return true;
    }

    /**
     * @param string[] $sourcePath
     * @param string $currentName
     * @param string $tempName
     * @throws FilesException
     */
    protected function backupOld(array $sourcePath, string $currentName, string $tempName): void
    {
        $fullPath = array_merge($sourcePath, [$currentName]);
        if ($this->libThumb->isHere($fullPath)) {
            if (!$this->libThumb->rename($sourcePath, $currentName, $tempName)) {
                throw new FilesException($this->getLang()->imThumbCannotRemoveCurrent());
            }
        }
    }

    /**
     * @param string[] $sourcePath
     * @param string $currentName
     * @param string $tempName
     * @throws FilesException
     */
    protected function restoreOld(array $sourcePath, string $currentName, string $tempName): void
    {
        $fullPath = array_merge($sourcePath, [$tempName]);
        if ($this->libThumb->isHere($fullPath)) {
            if (!$this->libThumb->rename($sourcePath, $tempName, $currentName)) {
                throw new FilesException($this->getLang()->imThumbCannotRestore());
            }
        }
    }

    /**
     * @param string[] $sourcePath
     * @param string $tempName
     * @throws FilesException
     */
    protected function removeOld(array $sourcePath, string $tempName): void
    {
        $fullPath = array_merge($sourcePath, [$tempName]);
        if ($this->libThumb->isHere($fullPath)) {
            if (!$this->libThumb->delete($sourcePath, $tempName)) {
                throw new FilesException($this->getLang()->imThumbCannotRemoveOld());
            }
        }
    }

    protected function randomName(): string
    {
        return uniqid('tmp_tmb_');
    }

    public function getGraphics(): Graphics
    {
        return $this->libGraphics;
    }

    public function getImage(): Sources\Image
    {
        return $this->libImage;
    }

    public function getThumb(): Sources\Thumb
    {
        return $this->libThumb;
    }
}
