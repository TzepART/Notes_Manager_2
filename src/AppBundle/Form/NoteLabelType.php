<?php

namespace AppBundle\Form;

use AppBundle\Entity\NoteLabel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteLabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('radius', RangeType::class,[
                'attr' => array(
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.01,
                    'class' => 'form-control range blue'
                ),
                'label' => 'Важность'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => NoteLabel::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_note_label';
    }
}
