<?php


namespace ImagineExtra\Filter;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WatermarkFilter extends AbstractFilter
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
        $resolver
            ->setDefaults(array(
                'size' => null,
                'position' => 'center',
            ))
            ->setAllowedValues(array(
                'position' => array(
                    'topleft', 'top', 'topright', 'left', 'center',
                    'right', 'bottomleft', 'bottom', 'bottomright',
                ),
            ))
            ->setRequired(array(
                'image',
                'position'
            ))
            ->setNormalizers(array(
                'size' => function(Options $options, $value) {
                    return substr($value, -1) == '%' ? substr($value, 0, -1) / 100 : $value;
                }
            ));
    }

    public function apply(ImageInterface $image)
    {
        $watermark = $this->imagine->open($this->rootPath . '/' . $this->getOption('image'));

        $size = $image->getSize();
        $watermarkSize = $watermark->getSize();

        $sizeOption = $this->getOption('size');

        // If 'null': Downscale if needed
        if (!$sizeOption && ($size->getWidth() < $watermarkSize->getWidth() || $size->getHeight() < $watermarkSize->getHeight())) {
            $sizeOption = 1.0;
        }

        if ($sizeOption) {
            $factor = $sizeOption * min($size->getWidth() / $watermarkSize->getWidth(), $size->getHeight() / $watermarkSize->getHeight());

            $watermark->resize(new Box($watermarkSize->getWidth() * $factor, $watermarkSize->getHeight() * $factor));
            $watermarkSize = $watermark->getSize();
        }

        switch ($this->getOption('position')) {
            case 'topleft':
                $x = 0;
                $y = 0;
                break;
            case 'top':
                $x = ($size->getWidth() - $watermarkSize->getWidth()) / 2;
                $y = 0;
                break;
            case 'topright':
                $x = $size->getWidth() - $watermarkSize->getWidth();
                $y = 0;
                break;
            case 'left':
                $x = 0;
                $y = ($size->getHeight() - $watermarkSize->getHeight()) / 2;
                break;
            case 'center':
                $x = ($size->getWidth() - $watermarkSize->getWidth()) / 2;
                $y = ($size->getHeight() - $watermarkSize->getHeight()) / 2;
                break;
            case 'right':
                $x = $size->getWidth() - $watermarkSize->getWidth();
                $y = ($size->getHeight() - $watermarkSize->getHeight()) / 2;
                break;
            case 'bottomleft':
                $x = 0;
                $y = $size->getHeight() - $watermarkSize->getHeight();
                break;
            case 'bottom':
                $x = ($size->getWidth() - $watermarkSize->getWidth()) / 2;
                $y = $size->getHeight() - $watermarkSize->getHeight();
                break;
            case 'bottomright':
                $x = $size->getWidth() - $watermarkSize->getWidth();
                $y = $size->getHeight() - $watermarkSize->getHeight();
                break;
            default:
                throw new \InvalidArgumentException("Unexpected position '{$this->getOption('position')}'");
                break;
        }

        return $image->paste($watermark, new Point($x, $y));
    }
}