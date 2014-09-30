<?php

namespace Ifensl\Bundle\PadManagerBundle\Form\FilterType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PadFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schoolYear', 'filter_number', array(
                'label' => 'Année Scolaire'
            ))
            ->add('title', 'filter_text', array(
                'label' => 'Titre'
            ))
            ->add('program', 'filter_entity', array(
                'class' => 'IfenslPadManagerBundle:Program',
                'attr' => array('class' => 'chosen-field'),
                'label' => 'Programme',
                'empty_value' => 'Choisissez un programme'
            ))
            ->add('unit', 'filter_entity', array(
                'class' => 'IfenslPadManagerBundle:Unit',
                'attr' => array('class' => 'chosen-field'),
                'label' => 'Unité d\'enseignement',
                'empty_value' => 'Choisissez une unité d\'enseignement'
            ))
            ->add('padOwner', 'filter_entity', array(
                'class' => 'IfenslPadManagerBundle:PadUser',
                'attr' => array('class' => 'chosen-field'),
                'label' => 'Créateur du pad',
                'empty_value' => 'Choisissez un créateur de pad'
            ))
        ;
    }

    public function getName()
    {
        return 'pad_filter';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}