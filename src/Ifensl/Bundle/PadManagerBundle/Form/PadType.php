<?php

namespace Ifensl\Bundle\PadManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;

class PadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('padOwner', 'paduser')
            ->add('padUsers', 'padusers')
            ->add('program', null, array(
                'required' => true,
                'empty_value' => 'Programme'
            ))
            ->add('unit', null, array(
                'required' => true,
                'empty_value' => 'UE'
            ))
            ->add('title', null, array(
                'label' => 'Titre',
                'attr' => array('placeholder' => 'Entrez un titre ici')
            ))
            ->add('save', 'submit', array(
                'label' => 'Créer'
            ))
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
