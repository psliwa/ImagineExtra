<?php


namespace ImagineExtra\Filter;


use Imagine\Filter\FilterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractFilter implements FilterInterface
{
    private $options;

    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    private function setOptions(array $options)
    {
        $this->options = $this->getOptionsResolver()->resolve($options);
    }

    protected function getOption($name)
    {
        return $this->options[$name];
    }

    private function getOptionsResolver()
    {
        $resolver = new OptionsResolver();

        $this->setupOptions($resolver);

        return $resolver;
    }

    abstract protected function setupOptions(OptionsResolverInterface $resolver);
}