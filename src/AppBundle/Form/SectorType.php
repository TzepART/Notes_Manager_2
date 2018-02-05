<?php

namespace AppBundle\Form;

use AppBundle\Entity\Sector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', null, [
                    'attr' => [
                        'placeholder'=> "Цвет"
                    ],
                    'required' => true,
                    'label' => 'Цвет',
                ]
            )
            ->add('name', null, [
                    'attr' => [
                        'placeholder'=> "Название категории"
                    ],
                    'required' => true,
                    'label' => 'Название категории',
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
