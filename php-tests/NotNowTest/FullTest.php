<?php

namespace SourcesTests;


use CommonTestClass;
use kalanis\kw_images\FilesHelper;
use kalanis\kw_images\Graphics;
use kalanis\kw_images\ImagesException;
use kalanis\kw_mime\MimeType;
use kalanis\kw_paths\Extras\ExtendDir;
use kalanis\kw_paths\PathsException;


class FullTest extends CommonTestClass
{
    protected function tearDown(): void
    {
        $tgt0 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt1 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'testimage.png';
        $tgt2 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'testimage.png.txt';
        $tgt10 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'testimage.png';
        $tgt11 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'testimage.png';
        $tgt12 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'testimage.png.txt';
        $tgt20 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $tgt21 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'tstimg.png';
        $tgt22 = $this->targetPath() . DIRECTORY_SEPARATOR . 'dumptree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'tstimg.png.txt';
        $tgt30 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tstimg.png';
        $tgt31 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'tmb' . DIRECTORY_SEPARATOR .  'tstimg.png';
        $tgt32 = $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'dsc' . DIRECTORY_SEPARATOR .  'tstimg.png.txt';
        if (is_file($tgt0)) {
            unlink($tgt0);
        }
        if (is_file($tgt1)) {
            unlink($tgt1);
        }
        if (is_file($tgt2)) {
            unlink($tgt2);
        }
        if (is_file($tgt10)) {
            unlink($tgt10);
        }
        if (is_file($tgt11)) {
            unlink($tgt11);
        }
        if (is_file($tgt12)) {
            unlink($tgt12);
        }
        if (is_file($tgt20)) {
            unlink($tgt20);
        }
        if (is_file($tgt21)) {
            unlink($tgt21);
        }
        if (is_file($tgt22)) {
            unlink($tgt22);
        }
        if (is_file($tgt30)) {
            unlink($tgt30);
        }
        if (is_file($tgt31)) {
            unlink($tgt31);
        }
        if (is_file($tgt32)) {
            unlink($tgt32);
        }
    }

    public function testBasics(): void
    {
        $lib = $this->getLib();
        $this->assertNotEmpty($lib->getLibImage());
        $this->assertNotEmpty($lib->getLibThumb());
        $this->assertNotEmpty($lib->getLibDesc());
        $this->assertNotEmpty($lib->getLibDirDesc());
        $this->assertNotEmpty($lib->getLibDirThumb());
    }

    /**
     * @throws ImagesException
     * @throws PathsException
     */
    public function testProcessing(): void
    {
        $lib = $this->getLib([
            'tmb_width' => 80,
            'tmb_height' => 50,
        ]);
        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');

        $this->assertTrue($lib->add('testtree' . DIRECTORY_SEPARATOR .  'testimage.png'));
        $this->assertTrue($lib->add('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'pl,okmijnuhb'));
        $this->assertTrue($lib->copy('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'dumptree'));
        $this->assertTrue($lib->move('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'dumptree' ,true));
        $this->assertTrue($lib->rename('dumptree' . DIRECTORY_SEPARATOR . 'testimage.png', 'tstimg.png'));
        $this->assertTrue($lib->delete('dumptree' . DIRECTORY_SEPARATOR . 'tstimg.png'));
    }

    /**
     * @throws ImagesException
     */
    public function testFailAdd(): void
    {
        $libFail = $this->getErrored(null, new FailedThumb($this->getExtDir(), $this->getGraphics()));

        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');

        $this->expectException(ImagesException::class);
        $libFail->add('testtree' . DIRECTORY_SEPARATOR .  'testimage.png');
    }

    /**
     * @param Files\Image|null $image
     * @param Files\Thumb|null $thumb
     * @param Files\Desc|null $desc
     * @param Files\DirThumb|null $dirThumb
     * @param Files\DirDesc|null $dirDesc
     * @throws ImagesException
     * @dataProvider failClassesProvider
     */
    public function testFailCopy(?Files\Image $image, ?Files\Thumb $thumb, ?Files\Desc $desc, ?Files\DirThumb $dirThumb, ?Files\DirDesc $dirDesc): void
    {
        $libFail = $this->getErrored($image, $thumb, $desc, $dirThumb, $dirDesc);
        $lib = $this->getLib();

        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib->add('testtree' . DIRECTORY_SEPARATOR . 'testimage.png', static::TEST_STRING);

        $this->assertFalse($libFail->copy('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'dumptree'));
    }

    /**
     * @param Files\Image|null $image
     * @param Files\Thumb|null $thumb
     * @param Files\Desc|null $desc
     * @param Files\DirThumb|null $dirThumb
     * @param Files\DirDesc|null $dirDesc
     * @throws ImagesException
     * @dataProvider errorClassesProvider
     */
    public function testErrorCopy(?Files\Image $image, ?Files\Thumb $thumb, ?Files\Desc $desc, ?Files\DirThumb $dirThumb, ?Files\DirDesc $dirDesc): void
    {
        $libFail = $this->getErrored($image, $thumb, $desc, $dirThumb, $dirDesc);
        $lib = $this->getLib();

        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib->add('testtree' . DIRECTORY_SEPARATOR . 'testimage.png', static::TEST_STRING);

        $this->expectException(ImagesException::class);
        $libFail->copy('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'dumptree');
    }

    /**
     * @param Files\Image|null $image
     * @param Files\Thumb|null $thumb
     * @param Files\Desc|null $desc
     * @param Files\DirThumb|null $dirThumb
     * @param Files\DirDesc|null $dirDesc
     * @throws ImagesException
     * @throws PathsException
     * @dataProvider failClassesProvider
     */
    public function testFailMove(?Files\Image $image, ?Files\Thumb $thumb, ?Files\Desc $desc, ?Files\DirThumb $dirThumb, ?Files\DirDesc $dirDesc): void
    {
        $libFail = $this->getErrored($image, $thumb, $desc, $dirThumb, $dirDesc);
        $lib = $this->getLib();

        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib->add('testtree' . DIRECTORY_SEPARATOR . 'testimage.png', static::TEST_STRING);

        $this->assertFalse($libFail->move('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'dumptree'));
    }

    /**
     * @param Files\Image|null $image
     * @param Files\Thumb|null $thumb
     * @param Files\Desc|null $desc
     * @param Files\DirThumb|null $dirThumb
     * @param Files\DirDesc|null $dirDesc
     * @throws ImagesException
     * @throws PathsException
     * @dataProvider errorClassesProvider
     */
    public function testErrorMove(?Files\Image $image, ?Files\Thumb $thumb, ?Files\Desc $desc, ?Files\DirThumb $dirThumb, ?Files\DirDesc $dirDesc): void
    {
        $libFail = $this->getErrored($image, $thumb, $desc, $dirThumb, $dirDesc);
        $lib = $this->getLib();

        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib->add('testtree' . DIRECTORY_SEPARATOR . 'testimage.png', static::TEST_STRING);

        $this->expectException(ImagesException::class);
        $libFail->move('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'dumptree');
    }

    /**
     * @param Files\Image|null $image
     * @param Files\Thumb|null $thumb
     * @param Files\Desc|null $desc
     * @param Files\DirThumb|null $dirThumb
     * @param Files\DirDesc|null $dirDesc
     * @throws ImagesException
     * @throws PathsException
     * @dataProvider failClassesProvider
     */
    public function testFailRename(?Files\Image $image, ?Files\Thumb $thumb, ?Files\Desc $desc, ?Files\DirThumb $dirThumb, ?Files\DirDesc $dirDesc): void
    {
        $libFail = $this->getErrored( $image, $thumb, $desc, $dirThumb, $dirDesc);
        $lib = $this->getLib();

        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib->add('testtree' . DIRECTORY_SEPARATOR . 'testimage.png', static::TEST_STRING);

        $this->assertFalse($libFail->rename('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'tstimg.png'));
    }

    /**
     * @param Files\Image|null $image
     * @param Files\Thumb|null $thumb
     * @param Files\Desc|null $desc
     * @param Files\DirThumb|null $dirThumb
     * @param Files\DirDesc|null $dirDesc
     * @throws ImagesException
     * @throws PathsException
     * @dataProvider errorClassesProvider
     */
    public function testErrorRename(?Files\Image $image, ?Files\Thumb $thumb, ?Files\Desc $desc, ?Files\DirThumb $dirThumb, ?Files\DirDesc $dirDesc): void
    {
        $libFail = $this->getErrored($image, $thumb, $desc, $dirThumb, $dirDesc);
        $lib = $this->getLib();

        copy($this->targetPath() . DIRECTORY_SEPARATOR . 'testimage.png', $this->targetPath() . DIRECTORY_SEPARATOR . 'testtree' . DIRECTORY_SEPARATOR . 'testimage.png');
        $lib->add('testtree' . DIRECTORY_SEPARATOR . 'testimage.png', static::TEST_STRING);

        $this->expectException(ImagesException::class);
        $libFail->rename('testtree' . DIRECTORY_SEPARATOR .  'testimage.png', 'tstimg.png');
    }

    /**
     * @return array
     * @throws ImagesException
     */
    public function failClassesProvider(): array
    {
        $graph = $this->getGraphics();
        $ext = $this->getExtDir($this->getParams());
        return [
            [new FailedImage($ext, $graph), null, null, null, null],
            [null, new FailedThumb($ext, $graph), null, null, null],
            [null, null, new FailedDesc($ext), null, null],
        ];
    }

    /**
     * @return array
     * @throws ImagesException
     */
    public function errorClassesProvider(): array
    {
        $graph = $this->getGraphics();
        $ext = $this->getExtDir($this->getParams());
        return [
            [new ErrorImage($ext, $graph), null, null, null, null],
            [null, new ErrorThumb($ext, $graph), null, null, null],
            [null, null, new ErrorDesc($ext), null, null],
        ];
    }

    /**
     * @param array
     * @return Files
     * @throws ImagesException
     */
    protected function getLib(array $params = [])
    {
        return FilesHelper::get(
            $this->targetPath() . DIRECTORY_SEPARATOR,
            array_merge($this->getParams(), $params)
        );
    }

    /**
     * @param Files\Image|null $libImage
     * @param Files\Thumb|null $libThumb
     * @param Files\Desc|null $libDesc
     * @param Files\DirThumb|null $libDirThumb
     * @param Files\DirDesc|null $libDirDesc
     * @return Files
     * @throws ImagesException
     */
    protected function getErrored(?Files\Image $libImage = null, ?Files\Thumb $libThumb = null, ?Files\Desc $libDesc = null, ?Files\DirThumb $libDirThumb = null, ?Files\DirDesc $libDirDesc = null): Files
    {
        $params = $this->getParams();
        $libExtDir = $this->getExtDir($params);
        $libGraphics = $this->getGraphics();
        return new Files(
            $libImage ?: new Files\Image($libExtDir, $libGraphics, $params),
            $libThumb ?: new Files\Thumb($libExtDir, $libGraphics, $params),
            $libDesc ?: new Files\Desc($libExtDir),
            $libDirDesc ?: new Files\DirDesc($libExtDir),
            $libDirThumb ?: new Files\DirThumb($libExtDir, $libGraphics, $params)
        );
    }

    protected function getParams(): array
    {
        return [
            'desc_dir' => 'dsc',
            'desc_file' => 'info',
            'desc_ext' => '.txt',
            'thumb_dir' => 'tmb',
        ];
    }

    /**
     * @return Graphics
     * @throws ImagesException
     */
    protected function getGraphics(): Graphics
    {
        return new Graphics(new Graphics\Format\Factory(), new MimeType());
    }

    /**
     * @param array $params
     * @return ExtendDir
     */
    protected function getExtDir(array $params = []): ExtendDir
    {
        return new ExtendDir(
            $this->targetPath() . DIRECTORY_SEPARATOR,
            $params['desc_dir'] ?? null,
            $params['desc_file'] ?? null,
            $params['desc_ext'] ?? null,
            $params['thumb_dir'] ?? null
        );
    }
}


class FailedImage extends Files\Image
{
    public function check(string $path): void
    {
        throw new ImagesException('mock test');
    }

    public function processUploaded(string $path): bool
    {
        throw new ImagesException('mock test');
    }

    public function copy(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }

    public function move(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }

    public function rename(string $path, string $sourceName, string $targetName, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }
}


class ErrorImage extends Files\Image
{
    public function copy(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }

    public function move(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }

    public function rename(string $path, string $sourceName, string $targetName, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }
}


class FailedThumb extends Files\Thumb
{
    public function create(string $path): void
    {
        throw new ImagesException('mock test');
    }

    public function copy(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }

    public function move(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }

    public function rename(string $path, string $sourceName, string $targetName, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }
}


class ErrorThumb extends Files\Thumb
{
    public function copy(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }

    public function move(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }

    public function rename(string $path, string $sourceName, string $targetName, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }
}


class FailedDesc extends Files\Desc
{
    public function get(string $path): string
    {
        throw new ImagesException('mock test');
    }

    public function set(string $path, string $content): void
    {
        throw new ImagesException('mock test');
    }

    public function copy(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }

    public function move(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }

    public function rename(string $path, string $sourceName, string $targetName, bool $overwrite = false): void
    {
        throw new ImagesException('mock test');
    }
}


class ErrorDesc extends Files\Desc
{
    public function copy(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }

    public function move(string $fileName, string $sourceDir, string $targetDir, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }

    public function rename(string $path, string $sourceName, string $targetName, bool $overwrite = false): void
    {
        throw new PathsException('mock test');
    }
}