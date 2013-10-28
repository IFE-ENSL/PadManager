<?php

namespace Ifensl\Bundle\PadManagerBundle\Exception;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;

class PadAlreadyExistException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct(Pad $pad)
    {
        parent::__construct(sprintf(
            "The Pad %s already exist for the user %s",
            $pad,
            $pad->getPadOwner()
        ));
    }
}
