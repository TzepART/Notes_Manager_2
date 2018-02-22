<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Note;
use AppBundle\Entity\User;
use AppBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NoteType
 * @package AppBundle\Form
 */
class NoteType extends AbstractType
{
    /**
     * @var User
     */
    private $user;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $note = $builder->getData();
        if($note->getUser() instanceof User){
            $this->setUser($note->getUser());
        }

        $builder
            ->add('title', null, [
                    'attr' => [
                        'placeholder'=> "Заголовок",
                        'class' => 'form-control'
                    ],
                    'required' => true,
                    'label' => 'Заголовок',
                ]
            )
            ->add('text', null, [
                    'attr' => [
                        'placeholder'=> "Текст",
                        'class' => 'form-control',
                        'row' => '3',
                    ],
                    'required' => true,
                    'label' => 'Текст',
                ]
            )
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false,
                'group_by' => 'circle',
                'label' => 'Категория',
                'query_builder' => function(CategoryRepository $repo) {
                    return $repo->queryCategoriesByUser($this->getUser());
                }
            ])
            ->add('importance', RangeType::class,[
                'attr' => array(
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.01,
                    'class' => 'form-control range blue'
                ),
                'label' => 'Важность'
            ]);
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
