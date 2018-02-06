<?php

namespace AppBundle\Form;

use AppBundle\Entity\NoteLabel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteLabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('radius');
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
