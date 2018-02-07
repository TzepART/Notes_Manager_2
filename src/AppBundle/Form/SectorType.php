<?php

namespace AppBundle\Form;

use AppBundle\Entity\Sector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                    'attr' => [
                        'placeholder'=> "Название категории",
                        'class' => 'form-control',
                    ],
                    'required' => true,
                    'label' => false,
                ]
            )
            ->add('color', ColorType::class, [
                    'attr' => [
                        'placeholder'=> "Цвет",
                        'class' => 'form-control',
                    ],
                    'required' => true,
                    'label' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Sector::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_sector_type';
    }
}
