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
                'by_reference' => false,
                'label' => false,
                'attr' => array(
                    'placeholder' => 'courriel',
                )
            ))
            ->add('program', 'choice', array(
                'label' => 'Programme',
                'choices' => $this->choices['programs']
            ))
            ->add('ue', 'choice', array(
                'label' => 'UE',
                'choices' => $this->choices['ues']
            ))
            ->add('subject', 'choice', array(
                'label' => 'Matière',
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