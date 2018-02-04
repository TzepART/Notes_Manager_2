<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //TODO добавить фильтрацию категорий
        $builder
            ->add('title', null, [
                    'attr' => [
                        'placeholder'=> "Заголовок"
                    ],
                    'required' => true,
                    'label' => 'Заголовок',
                ]
            )
            ->add('text', null, [
                    'attr' => [
                        'placeholder'=> "Текст"
                    ],
                    'required' => true,
                    'label' => 'Текст',
                ]
            )
            ->add('category',EntityType::class,[
                'class' => Category::class
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Note::class
        ));
    }
}
