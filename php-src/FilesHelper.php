<?php

namespace kalanis\kw_images;


use kalanis\kw_files\Extended\Config;
use kalanis\kw_files\Extended\Processor;
use kalanis\kw_files\Interfaces\IFLTranslations;
use kalanis\kw_files\Processing\Volume;
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
     * @return Files
     * @throws ImagesException
     */
    public static function get(string $webRootDir, array $params = [], ?IIMTranslations $langIm = null, ?IFLTranslations $langFl = null): Files
    {
        $fileConf = (new Config())->setData($params);
        $libProcessFiles = new Volume\ProcessFile($webRootDir, $langFl);
        $libProcessNodes = new Volume\ProcessNode($webRootDir);
        $libProcessor = new Processor( ## extend dir props
            new Volume\ProcessDir($webRootDir, $langFl),
            $libProcessNodes,
            $fileConf
        );
        $libGraphics = new Graphics\Processor(new Graphics\Format\Factory(), $langIm);
        $thumbConf = (new Graphics\ThumbConfig())->setData($params);
        return new Files(  ## process images
            (new Graphics($libGraphics, new MimeType(), $langIm))->setSizes($thumbConf),
            new Sources\Image($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
            new Sources\Thumb($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
            new Sources\Desc($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
            new Sources\DirDesc($libProcessNodes, $libProcessFiles, $fileConf, $langIm),
            new Sources\DirThumb($libProcessNodes, $libProcessFiles, $fileConf, $langIm)
        );
    }
}
