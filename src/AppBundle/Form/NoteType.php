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
        $builder
            ->add('title', null, [
                    'attr' => [
                        'placeholder'=> "Имя"
                    ],
                    'required' => true,
                    'label' => 'Название',
                ]
            )
            ->add('text', null, [
                    'attr' => [
                        'placeholder'=> "Количество уровней"
                    ],
                    'required' => true,
                    'label' => 'Количество уровней',
                ]
            )
            ->add('category',EntityType::class,[
                'data_class' => Category::class
            ])
            ->add('countLayer')
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
