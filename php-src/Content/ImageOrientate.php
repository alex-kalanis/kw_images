<?php

namespace kalanis\kw_images\Content;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IExifConstants;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_images\Interfaces\ISizes;
use kalanis\kw_images\Sources;
use kalanis\kw_images\Traits\TLang;
use kalanis\kw_mime\MimeException;
use kalanis\kw_paths\PathsException;


/**
 * Class ImageOrientate
 * Orientate image against the data in its exif
 * @package kalanis\kw_images\Content
 * @link https://stackoverflow.com/questions/7489742/php-read-exif-data-and-adjust-orientation
 * @link https://jdhao.github.io/2019/07/31/image_rotation_exif_info/#exif-orientation-flag
 * The main difference between rotation and orientation classes is from where came the data which will define what kind
 * of operation will be processed. Orientation has them in image EXIF, rotation got them from external input.
 */
class ImageOrientate
{
    use TLang;

    protected Sources\Image $libImage;
    protected Graphics $libGraphics;
    protected ISizes $config;

    public function __construct(Graphics $graphics, ISizes $config, Sources\Image $image, ?IIMTranslations $lang = null)
    {
        $this->setImLang($lang);
        $this->libImage = $image;
        $this->libGraphics = $graphics;
        $this->config = $config;
    }

    /**
     * @param string[] $sourcePath
     * @param string[]|null $targetPath
     * @throws FilesException
     * @throws ImagesException
     * @throws MimeException
     * @throws PathsException
     * @return bool
     */
    public function process(array $sourcePath, ?array $targetPath = null): bool
    {
        $sourceFull = array_values($sourcePath);
        $targetFull = $targetPath ? array_values($targetPath) : $sourceFull;

        $tempPath = strval(tempnam(sys_get_temp_dir(), $this->config->getTempPrefix()));

        // get from the storage
        $resource = $this->libImage->get($sourceFull);
        if (empty($resource)) {
            @unlink($tempPath);
            throw new FilesException($this->getImLang()->imThumbCannotGetBaseImage());
        }

        if (false === @file_put_contents($tempPath, $resource)) {
            // @codeCoverageIgnoreStart
            @unlink($tempPath);
            throw new FilesException($this->getImLang()->imThumbCannotStoreTemporaryImage());
        }
        // @codeCoverageIgnoreEnd

        try {
            $exif = @exif_read_data($tempPath);
            if (false === $exif) {
                throw new ImagesException($this->getImLang()->imImageCannotOrientate());
            }

            // now process image locally
            if (!empty($exif['Orientation'])) {
                $orientate = intval($exif['Orientation']);
                $this->libGraphics->rotate(
                    $this->getAngle($orientate),
                    $this->getMirror($orientate),
                    $tempPath,
                    $sourceFull,
                    $targetFull
                );
            }
        } catch (ImagesException $ex) {
            // clear when fails
            @unlink($tempPath);
            throw $ex;
        }

        // return result to the storage as new file
        $result = @file_get_contents($tempPath);
        if (false === $result) {
            // @codeCoverageIgnoreStart
            @unlink($tempPath);
            throw new FilesException($this->getImLang()->imThumbCannotLoadTemporaryImage());
        }
        // @codeCoverageIgnoreEnd

        $set = $this->libImage->set($targetFull, $result);
        @unlink($tempPath);
        return $set;
    }

    /**
     * @param int $orientation
     * @throws ImagesException
     * @return float
     */
    protected function getAngle(int $orientation): float
    {
        switch ($orientation) {
            case IExifConstants::EXIF_ORIENTATION_ON_LEFT:
            case IExifConstants::EXIF_ORIENTATION_MIRROR_ON_LEFT:
                return 90;
            case IExifConstants::EXIF_ORIENTATION_UPSIDE_DOWN:
            case IExifConstants::EXIF_ORIENTATION_MIRROR_UPSIDE_DOWN:
                return 180;
            case IExifConstants::EXIF_ORIENTATION_ON_RIGHT:
            case IExifConstants::EXIF_ORIENTATION_MIRROR_ON_RIGHT:
                return 270;
            case IExifConstants::EXIF_ORIENTATION_NORMAL:
            case IExifConstants::EXIF_ORIENTATION_MIRROR_SIMPLE:
                return 0;
                // @codeCoverageIgnoreStart
            default:
                throw new ImagesException($this->getImLang()->imImageCannotOrientate());
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @param int $orientation
     * @return int|null
     */
    protected function getMirror(int $orientation): ?int
    {
        switch ($orientation) {
            case IExifConstants::EXIF_ORIENTATION_MIRROR_UPSIDE_DOWN:
            case IExifConstants::EXIF_ORIENTATION_MIRROR_ON_RIGHT:
            case IExifConstants::EXIF_ORIENTATION_MIRROR_ON_LEFT:
            case IExifConstants::EXIF_ORIENTATION_MIRROR_SIMPLE:
                return IMG_FLIP_HORIZONTAL;
            default:
                return null;
        }
    }

    public function getImage(): Sources\Image
    {
        return $this->libImage;
    }
}
