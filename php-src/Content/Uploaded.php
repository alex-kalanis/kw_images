<?php

namespace kalanis\kw_images\Content;


use kalanis\kw_files\FilesException;
use kalanis\kw_images\ImagesException;
use kalanis\kw_images\Interfaces\IIMTranslations;
use kalanis\kw_images\Interfaces\ISizes;
use kalanis\kw_images\Sources;
use kalanis\kw_images\TLang;
use kalanis\kw_paths\Interfaces\IPaths;
use kalanis\kw_paths\Stuff;


/**
 * Class Uploaded
 * Process uploaded content
 * @package kalanis\kw_images\Content
 */
class Uploaded
{
    use TLang;

    /** @var Processor */
    protected $processor = null;
    /** @var ISizes */
    protected $config = null;
    /** @var Sources\Desc */
    protected $libDesc = null;

    public function __construct(Processor $processor, ISizes $config, Sources\Desc $desc, ?IIMTranslations $lang = null)
    {
        $this->setLang($lang);
        $this->processor = $processor;
        $this->config = $config;
        $this->libDesc = $desc;
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @param string $name
     * @throws FilesException
     * @return string
     */
    public function findFreeName(array $wantedPath, string $name): string
    {
        $name = Stuff::canonize($name);
        $ext = Stuff::fileExt($name);
        if (0 < mb_strlen($ext)) {
            $ext = IPaths::SPLITTER_DOT . $ext;
        }
        $fileName = Stuff::fileBase($name);
        return $this->processor->getImage()->findFreeName($wantedPath, $fileName, $ext);
    }

    /**
     * @param string[] $wantedPath where we want to store the file
     * @param string $sourcePath where the file is accessible after upload
     * @param string $description
     * @param bool $hasThumb
     * @param bool $wantResize
     * @throws FilesException
     * @throws ImagesException
     * @return bool
     */
    public function process(array $wantedPath, string $sourcePath = '', string $description = '', bool $hasThumb = true, bool $wantResize = false): bool
    {
        $fullPath = array_values($wantedPath);
        $fileName = array_pop($wantedPath);
        // check file
        $this->processor->getGraphics()->setSizes($this->config)->check($sourcePath);

        // resize if set
        if ($wantResize) {
            $this->processor->getGraphics()->setSizes($this->config)->resize($sourcePath, $fileName);
        }

        // store image
        $uploaded = @file_get_contents($sourcePath);
        if (false === $uploaded) {
            return false;
        }
        $this->processor->getImage()->set($wantedPath, $uploaded);

        // thumbs
        $this->processor->getThumb()->delete($wantedPath, $fileName);
        if ($hasThumb) {
            $this->processor->update(
                $this->processor->getImage()->getPath($fullPath),
                $this->processor->getThumb()->getPath($fullPath)
            );
        }

        // description
        if (!empty($description)) {
            $this->libDesc->set($fullPath, $description);
        } else {
            $this->libDesc->delete($fullPath, $fileName);
        }

        return true;
    }
}
