<?php

namespace Sunday\UserBundle\Form\Type;

use Sunday\UserBundle\Form\DataTransformer\BlobToImageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class BlobHiddenType extends AbstractType
{
    protected $rootDir;

    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new BlobToImageTransformer($this->rootDir);
        $builder->addViewTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'invalid_message' => 'The blob does not exist',
        ]);
    }

    public function getParent()
    {
        return HiddenType::class;
    }
}