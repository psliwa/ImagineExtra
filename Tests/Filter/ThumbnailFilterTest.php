<?php


namespace ImagineExtra\Tests\Filter;


use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use ImagineExtra\Filter\ThumbnailFilter;

class ThumbnailFilterTest extends AbstractTestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function givenMode_createThumbnailWithExpectedSize($mode, $size, $expectedWidth, $expectedHeight)
    {
        //given

        $filter = new ThumbnailFilter(array(
            'mode' => $mode,
            'size' => $size,
        ));

        $image = $this->create100x80BlackImage();

        //when

        $actualImage = $filter->apply($image);

        //then

        $this->assertEquals(new Box($expectedWidth, $expectedHeight), $actualImage->getSize());
    }

    public function dataProvider()
    {
        return array(
            array(ImageInterface::THUMBNAIL_OUTBOUND, array(50, 50), 50, 50),
            array(ImageInterface::THUMBNAIL_INSET, array(50, 50), 50, round(50*0.8)),
            array(ImageInterface::THUMBNAIL_OUTBOUND, array(50, 40), 50, 40),
            array(ImageInterface::THUMBNAIL_INSET, array(50, 30), round(50*0.75), 30),
        );
    }
}
 