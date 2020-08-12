<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 8/12/20
 * Time: 3:19 AM
 */

namespace App\DTO;


use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractRequest
{
    public $options;
    public function __construct(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->map($resolver, $options);
    }

    abstract protected function configureOptions(OptionsResolver $options);

    protected function map(OptionsResolver $resolver, $options)
    {
         $resolver->resolve($options);
    }
}