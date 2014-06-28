<?php


namespace ImagineExtra\Tests\Filter;

use ImagineExtra\Filter\WatermarkFilter;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

class WatermarkFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Imagine
     */
    private $imagine;

    protected function setUp()
    {
        $this->imagine = new Imagine();
    }

    /**
     * @test
     * @dataProvider watermarkPositionProvider
     */
    public function testWatermarkPosition($position, $watermarkX, $watermarkY, $imageX = 0.5, $imageY = 0.5)
    {
        //given

        $filter = new WatermarkFilter($this->imagine, __DIR__.'/../Resources', array(
            'position' => $position,
            'image' => '10x10-white.png',
        ));

        $image = $this->imagine->open(__DIR__.'/../Resources/100x80-black.png');

        //when

        $actualImage = $filter->apply($image);

        //then

        $watermarkX = $watermarkX*$actualImage->getSize()->getWidth();
        $watermarkY = $watermarkY*$actualImage->getSize()->getHeight();
        $this->assertEquals('#ffffff', $actualImage->getColorAt(new Point($watermarkX, $watermarkY)));

        $imageX = $imageX*$actualImage->getSize()->getWidth();
        $imageY = $imageY*$actualImage->getSize()->getHeight();
        $this->assertEquals('#000000', $actualImage->getColorAt(new Point($imageX, $imageY)));
    }

    public function watermarkPositionProvider()
    {
        return array(
            array('topleft', 0, 0),
            array('top', 0.5, 0),
            array('topright', 0.99, 0),
            array('left', 0, 0.5),
            array('center', 0.5, 0.5, 0, 0),
            array('right', 0.99, 0.5),
            array('bottomleft', 0, 0.99),
            array('bottom', 0.5, 0.99),
            array('bottomright', 0.99, 0.99),
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidOptionsProvider
     */
    public function givenInvalidOptions_throwEx($options)
    {
        new WatermarkFilter($this->imagine, __DIR__.'/../Resources', $options);
    }

    public function invalidOptionsProvider()
    {
        return array(
            array(
                array(),
            ),
            //invalid position
            array(
                array('position' => 'invalid position', 'image' => 'abc'),
            ),
            //image missing
            array(
                array('position' => 'center'),
            ),
        );
    }

    /**
     * @test
     */
    public function givenSize_applySizeToWatermark()
    {
        //given

        $size = 0.5;

        $filter = new \ImagineExtra\Filter\WatermarkFilter($this->imagine, __DIR__.'/../Resources', array(
            'position' => 'topleft',
            'image' => '10x10-white.png',
            'size' => $size,
        ));

        $image = $this->imagine->open(__DIR__.'/../Resources/100x80-black.png');

        //when

        $actualImage = $filter->apply($image);

        //then

        $smallerSide = min($actualImage->getSize()->getWidth(), $actualImage->getSize()->getHeight());
        $expectedWatermarkSize = $smallerSide*$size;

        $this->assertEquals('#ffffff', $actualImage->getColorAt(new Point(0, 0)));
        $this->assertEquals('#ffffff', $actualImage->getColorAt(new Point($expectedWatermarkSize - 1, $expectedWatermarkSize - 1)));
        $this->assertEquals('#000000', $actualImage->getColorAt(new Point($expectedWatermarkSize + 1, 0)));
    }
}
 