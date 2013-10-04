<?php

namespace Ifensl\Bundle\PadManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PadType extends AbstractType
{
    private $choices;

    /**
     * Constructor
     */
    public function __construct($choices)
    {
        $this->choices = $choices;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('padUsers','collection', array(
                'type' => new PadUserType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'courriel'
                )
            ))
            ->add('program', 'choice', array(
                'attr' => array('class' => 'program'),
                'label' => false,
                'empty_value' => 'Programme',
                'choices' => $this->choices['programs']
            ))
            ->add('ue', 'choice', array(
                'attr' => array('class' => 'ue'),
                'label' => false,
                'empty_value' => 'UE',
                'choices' => $this->choices['ues']
            ))
            ->add('subject', 'choice', array(
                'attr' => array('class' => 'subject'),
                'label' => false,
                'empty_value' => 'Matière',
                'choices' => $this->choices['subjects']
            ))
            ->add('submit', 'submit', array('label' => 'Créer'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ifensl\Bundle\PadManagerBundle\Entity\Pad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ifensl_padmanagerbundle_pad';
    }
}