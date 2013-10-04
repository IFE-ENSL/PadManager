<?php

namespace Ifensl\Bundle\PadManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PadType extends AbstractType
{
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
            ->add('program', 'entity', array(
                'class' => 'IfenslPadManagerBundle:Program',
                'empty_value' => 'Programme',
                'label' => false,
                'attr' => array('class' => 'program')
            ))
            ->add('ue', 'entity', array(
                'class' => 'IfenslPadManagerBundle:UE',
                'empty_value' => 'UE',
                'label' => false,
                'attr' => array('class' => 'ue')
            ))
            ->add('subject', 'entity', array(
                'class' => 'IfenslPadManagerBundle:Subject',
                'empty_value' => 'Matière',
                'label' => false,
                'attr' => array('class' => 'subject')
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