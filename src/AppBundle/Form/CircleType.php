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
                        'placeholder'=> "Имя"
                    ],
                    'required' => true,
                    'label' => 'Название',
                ]
            )
            ->add('countLayer', null, [
                    'attr' => [
                        'placeholder'=> "Количество уровней"
                    ],
                    'required' => true,
                    'label' => 'Количество уровней',
                ]
            )
            ->add('sectors', CollectionType::class,[
                'entry_type' => SectorType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Количество уровней',
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
