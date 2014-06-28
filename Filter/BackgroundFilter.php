<?php


namespace ImagineExtra\Filter;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette;
use Imagine\Image\Point;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BackgroundFilter extends AbstractFilter
{
    private $imagine;
    private $palette;

    public function __construct(ImagineInterface $imagine, array $options)
    {
        parent::__construct($options);
        $this->imagine = $imagine;
        $this->palette = new Palette\RGB();
    }

    protected function setupOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'color' => '#fff',
            'size' => array(null, null),
        ));

        $resolver->setAllowedTypes(array(
            'color' => 'string',
            'size' => 'array',
        ));
        $resolver->setNormalizers(array(
            'size' => Validators::numberPair('size'),
        ));
    }

    public function apply(ImageInterface $image)
    {
        $background = $this->palette->color($this->getOption('color'));
        $topLeft = new Point(0, 0);
        $size = $image->getSize();

        if ($this->getOption('size')) {
            list($width, $height) = $this->getOption('size');

            $size = new Box($width, $height);
            $topLeft = new Point(($width - $image->getSize()->getWidth()) / 2, ($height - $image->getSize()->getHeight()) / 2);
        }

        $canvas = $this->imagine->create($size, $background);

        return $canvas->paste($image, $topLeft);
    }
}