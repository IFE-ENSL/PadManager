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
          ->add('state')
          ->add('schoolYear')
          ->add('program')
          ->add('unit')
          ->add('subject')
          ->add('padUsers')
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
