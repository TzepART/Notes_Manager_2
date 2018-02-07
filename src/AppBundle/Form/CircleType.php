<?php

namespace AppBundle\Form;

use AppBundle\Entity\Circle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CircleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                    'attr' => [
                        'placeholder'=> "Название окружности",
                        'class' => 'form-control',
                    ],
                    'required' => true,
                    'label' => false
                ]
            )
            ->add('countLayer', null, [
                    'attr' => [
                        'placeholder'=> "Количество уровней",
                        'class' => 'form-control',
                    ],
                    'required' => true,
                    'label' => false,
                ]
            )
            ->add('sectors', CollectionType::class,[
                'entry_options'  =>[
                    'attr'      => [
                        'class' => 'form-group create_sector'
                    ]
                ],
                'entry_type' => SectorType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Circle::class,
            )
        );
    }
}
