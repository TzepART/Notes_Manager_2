<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Note;
use AppBundle\Entity\User;
use AppBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'class' => Category::class,
                'required' => false,
                'group_by' => 'circle',
                'query_builder' => function(CategoryRepository $repo) {
                    return $repo->queryCategoriesByUser($this->getUser());
                },
            ])
            ->add('noteLabel', NoteLabelType::class, [
                    'attr' => [
                        'placeholder'=> "Важность"
                    ],
                    'required' => false,
                    'label' => false,
                ]
            )
            ->add('save', SubmitType::class)
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
