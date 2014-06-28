<?php


namespace ImagineExtra\Filter;

use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PasteFilter extends AbstractFilter
{
    private $imagine;
    private $rootPath;

    public function __construct(ImagineInterface $imagine, $rootPath, array $options)
    {
        parent::__construct($options);

        $this->imagine = $imagine;
        $this->rootPath = $rootPath;
    }

    protected function setupOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'start' => null,
            'image' => null,
        ));

        $resolver->setAllowedTypes(array(
            'start' => 'array',
            'image' => 'string',
        ));
        $resolver->setNormalizers(array(
            'start' => Validators::numberPair('start'),
        ));
        $resolver->setRequired(array('start', 'image'));
    }

    public function apply(ImageInterface $image)
    {
        list($x, $y) = $this->getOption('start');
        $destImage = $this->imagine->open($this->rootPath.'/'.$this->getOption('image'));

        return $image->paste($destImage, new Point($x, $y));
    }
}