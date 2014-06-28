<?php


namespace ImagineExtra\Tests\Filter;

use Imagine\Gd\Imagine;

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
} 