<?php

namespace BasicTests;


use CommonTestClass;
use kalanis\kw_images\Files;


class SizesTest extends CommonTestClass
{
    /**
     * @param int $currentWidth
     * @param int $currentHeight
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $expectedWidth
     * @param int $expectedHeight
     * @dataProvider sizesProvider
     */
    public function testSizes(int $currentWidth, int $currentHeight, int $maxWidth, int $maxHeight, int $expectedWidth, int $expectedHeight): void
    {
        $lib = new XSizes();
        $result = $lib->xCalculateSize($currentWidth, $maxWidth, $currentHeight, $maxHeight);
        $this->assertEquals($expectedWidth, $result['width']);
        $this->assertEquals($expectedHeight, $result['height']);
    }

    public function sizesProvider(): array
    {
        return [
            [20, 20, 35, 35, 20, 20], // sizes inside the limit
            [35, 35, 20, 20, 20, 20], // smaller!
            [55, 20, 30, 50, 30, 10], // one size larger - new sizes by ratio
            [80, 15, 40, 40, 40,  7],
            [15, 80, 40, 40,  7, 40],
        ];
    }
}


class XSizes
{
    use Files\TSizes;

    public function xCalculateSize(int $currentWidth, int $maxWidth, int $currentHeight, int $maxHeight)
    {
        return $this->calculateSize($currentWidth, $maxWidth, $currentHeight, $maxHeight);
    }
}
