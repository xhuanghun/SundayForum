<?php

namespace Sunday\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sunday\UserBundle\Form\Type\BlobHiddenType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'business_unit',
                EntityType::class,
                [
                    'class' => 'SundayOrganizationBundle:BusinessUnit',
                    'label'  => 'form.business_unit', 'translation_domain' => 'FOSUserBundle',
                    'placeholder' => 'Choose one',
                    'empty_data'  => '0',
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('b')
                                ->select('bu')
                                ->from('Sunday\OrganizationBundle\Entity\BusinessUnit', 'bu')
                                ->where('bu.hidden = 0');
                    }
                ]
            )
            ->add(
                'avatarFile',
                BlobHiddenType::class
            );

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'sunday_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

}