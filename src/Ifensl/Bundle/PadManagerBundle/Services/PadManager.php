<?php

/**
 *
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Ifensl\Bundle\PadManagerBundle\Service;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Entity\PadUser;

class PadManager
{
    protected $entityManager;
    protected $tokenGenerator;
    protected $mailer;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function createPad(Pad $pad)
    {

    }

    /**
     *
     */
    public function invitePadUser(Pad $pad, PadUser $user)
    {

    }

    /**
     *
     */
    public function removePadUser(Pad $pad, PadUser $user)
    {

    }

    /**
     *
     */
    protected function generatePadTokens(Pad & $pad)
    {
        $salt = $this->getTokenGenerator()->generateSalt();
        $pad->setPrivateToken($this->getTokenGenerator()->generateToken($pad, 'private', $salt));
        $pad->setPublicToken($this->getTokenGenerator()->generateToken($pad, 'public', $salt));
    }
}
