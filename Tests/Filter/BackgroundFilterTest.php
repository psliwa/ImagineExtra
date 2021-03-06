<?php


namespace ImagineExtra\Tests\Filter;


use ImagineExtra\Filter\BackgroundFilter;
use Imagine\Image\Box;
use Imagine\Image\Point;

class BackgroundFilterTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function givenSizeGreaterThanImage_centerImageOnBackground()
    {
        //given

        $backgroundColor = '#cccccc';

        $width = $height = 120;

        $filter = new BackgroundFilter($this->imagine, array(
            'color' => $backgroundColor,
            'size' => array($width, $height),
        ));

        $image = $this->create100x80BlackImage();

        //when

        $actualImage = $filter->apply($image);

        //then

        $this->assertEquals(new Box($width, $height), $actualImage->getSize());

        $this->assertEquals($backgroundColor, (string) $actualImage->getColorAt(new Point(9, 39)), 'this should be background area');

        $this->assertEquals('#000000', (string) $actualImage->getColorAt(new Point(11, 41)), 'this should be original image area');
        $this->assertEquals('#000000', (string) $actualImage->getColorAt(new Point(109, 99)), 'this should be original image area');

        $this->assertEquals($backgroundColor, (string) $actualImage->getColorAt(new Point(111, 101)), 'this should be background area');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidOptionsProvider
     */
    public function givenInvalidOptions_throwEx(array $invalidOptions)
    {
        new BackgroundFilter($this->imagine, $invalidOptions);
    }

    public function invalidOptionsProvider()
    {
        return array(
            array(
                array('color' => 5),
            ),
            array(
                array('unsupported-option' => 'value'),
            ),
            array(
                array('size' => 'invalid-size'),
            ),
            array(
                array('size' => array('invalid size')),
            ),
        );
    }
}