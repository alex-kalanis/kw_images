<?php

namespace kalanis\kw_images;


use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\Extended\Processor;
use kalanis\kw_files\Interfaces\IFLTranslations;
use kalanis\kw_files\Processing\Volume;
use kalanis\kw_images\Content\BasicOperations;
use kalanis\kw_images\Content\Uploaded;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_mime\MimeType;


/**
 * Class Files
 * Operations over files
 * @package kalanis\kw_images
 */
class FilesHelper
{
    /**
     * @param string $webRootDir
     * @param array<string, string|int> $params
     * @param IIMTranslations|null $langIm
     * @param IFLTranslations|null $langFl
     * @throws ImagesException
     * @return Uploaded
     */
    public static function getUpload(string $webRootDir, array $params = [], ?IIMTranslations $langIm = null, ?IFLTranslations $langFl = null): Uploaded
    {
        $libProcessFiles = new Volume\ProcessFile($webRootDir, $langFl);
        $libProcessNodes = new Volume\ProcessNode($webRootDir);
        $fileConf = (new Config())->setData($params);
        return new Uploaded(  // process uploaded images
            new Content\Processor(
                new Graphics(new Graphics\Processor(new Graphics\Format\Factory(), $langIm), new MimeType(), $langIm),
                (new Graphics\ThumbConfig())->setData($params),
                new Sources\Image($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
                new Sources\Thumb($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
                $langIm
            ),
            (new Graphics\ImageConfig())->setData($params),
            new Sources\Desc($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
            $langIm
        );
    }

    /**
     * @param string $webRootDir
     * @param array<string, string|int> $params
     * @param IIMTranslations|null $langIm
     * @param IFLTranslations|null $langFl
     * @return BasicOperations
     */
    public static function getOperations(string $webRootDir, array $params = [], ?IIMTranslations $langIm = null, ?IFLTranslations $langFl = null): BasicOperations
    {
        $fileConf = (new Config())->setData($params);
        $libProcessFiles = new Volume\ProcessFile($webRootDir, $langFl);
        $libProcessNodes = new Volume\ProcessNode($webRootDir);
        return new BasicOperations(  // operations with images
            new Sources\Image($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
            new Sources\Thumb($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
            new Sources\Desc($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
        );
    }

    /**
     * @param string $webRootDir
     * @param array<string, string|int> $params
     * @param IFLTranslations|null $langFl
     * @return Processor
     */
    public static function getDirs(string $webRootDir, array $params = [], ?IFLTranslations $langFl = null): Processor
    {
        $fileConf = (new Config())->setData($params);
        $libProcessNodes = new Volume\ProcessNode($webRootDir);
        return new Processor( // extend dir props
            new Volume\ProcessDir($webRootDir, $langFl),
            $libProcessNodes,
            $fileConf
        );
    }
}
