<?php


namespace ImagineExtra\Filter;


use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;

class Validators
{
    public static function numberPair($name)
    {
        return function(Options $options, $value) use($name) {
            if(count($value) !== 2 || array_keys($value) !== array(0, 1)) {
                throw new InvalidOptionsException(sprintf('Option %s should be an array that contains two elements indexed 0 and 1, "%s" given', $name, json_encode($value)));
            }
            return $value;
        };
    }

} 