<?php


namespace ImagineExtra\Tests\Filter;

use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Imagine
     */
    protected $imagine;

    protected function setUp()
    {
        $this->imagine = new Imagine();
    }

    /**
     * @return \Imagine\Gd\Image|ImageInterface
     */
    protected function create100x80BlackImage()
    {
        return $this->imagine->open(__DIR__ . '/../Resources/100x80-black.png');
    }
} 