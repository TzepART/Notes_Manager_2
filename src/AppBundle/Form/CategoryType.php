<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategoryType extends AbstractType
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
                        'placeholder'=> "Название"
                    ],
                    'required' => true,
                    'label' => 'Название',
                ]
            )
            ->add('color', ColorType::class, [
                    'attr' => [
                        'placeholder'=> "Цвет",
                        'type' => 'color',
                    ],
                    'required' => true,
                    'label' => 'Цвет',
                ]
            )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Category::class,
            )
        );
    }
}
