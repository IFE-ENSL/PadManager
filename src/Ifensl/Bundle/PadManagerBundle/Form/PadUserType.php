<?php

namespace Ifensl\Bundle\PadManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ifensl\Bundle\PadManagerBundle\Form\Type\DataTransformer\PadUserToEmailTransformer;

class PadUserType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new PadUserToEmailTransformer($this->om);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'placeHolder' => 'courriel'
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'email';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'paduser';
    }
}
