<?php

namespace Ifensl\Bundle\PadManagerBundle\Form\Type\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Ifensl\Bundle\PadManagerBundle\Entity\PadUser;

class PadUserToEmailTransformer implements DataTransformerInterface
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
     * Transforms an object (PadUser) to a string (email).
     *
     * @param  PadUser|null $padUser
     * @return string
     */
    public function transform($padUser)
    {
        if (!$padUser) {
            return null;
        }

        return $padUser->getEmail();
    }

    /**
     * Transforms a string (email) to a object (padUser).
     *
     * @param  string $email
     * @return PadUser|null
     * @throws TransformationFailedException if object (padUser) is not found.
     */
    public function reverseTransform($email)
    {
        if (null === $email) {
            return null;
        }

        $padUser = $this->om
            ->getRepository('IfenslPadManagerBundle:PadUser')
            ->findOneBy(array('email' => $email))
        ;

        if (null === $padUser) {
            $padUser = new PadUser($email);
        }

        return $padUser;
    }
}
