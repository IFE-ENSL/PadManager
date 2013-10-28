<?php

namespace Ifensl\Bundle\PadManagerBundle\Exception;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;

class PadAlreadyExistException extends \Exception
{
    protected $pad;

    /**
     * {@inheritdoc}
     */
    public function __construct(Pad $pad)
    {
        $this->pad = $pad;
        parent::__construct(sprintf(
            "The Pad %s already exist for the user %s",
            $pad,
            $pad->getPadOwner()
        ));
    }

    /**
     * Get Pad
     *
     * @return Pad
     */
    public function getPad()
    {
        return $this->pad;
    }
}
