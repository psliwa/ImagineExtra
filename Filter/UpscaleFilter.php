<?php


namespace ImagineExtra\Filter;

use Imagine\Filter\Basic\Resize;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Upscale filter
 *
 * @author Maxime Colin <contact@maximecolin.fr>
 */
class UpscaleFilter extends AbstractFilter
{
    protected function setupOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
            ))
            ->setAllowedTypes(array(
                'min' => 'array',
            ))
            ->setRequired(array('min'))
            ->setNormalizers(array(
                'min' => Validators::numberPair('min'),
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function apply(ImageInterface $image)
    {
        list($width, $height) = $this->getOption('min');

        $size = $image->getSize();
        $origWidth = $size->getWidth();
        $origHeight = $size->getHeight();

        if ($origWidth < $width || $origHeight < $height) {

            $widthRatio = $width / $origWidth ;
            $heightRatio = $height / $origHeight;

            $ratio = $widthRatio > $heightRatio ? $widthRatio : $heightRatio;

            $filter = new Resize(new Box($origWidth * $ratio, $origHeight * $ratio));

            return $filter->apply($image);
        }

        return $image;
    }
}