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

class PadTokenGenerator
{
    /**
     * Generate Token
     *
     * @param string $type
     * @return string
     */
    public function generateToken(Pad $pad, $type, $salt = null)
    {
        return sprintf("%s__%s",
            md5(sprintf("%s%s%s%s",
                $pad->getProgram(),
                $pad->getUnit(),
                $pad->getSubject(),
                $pad->getOwner(),
                $salt
            )),
            sprintf("%u", crc32($type))
        );
    }

    /**
     * Generate salt
     *
     * @return string
     */
    public function generateSalt()
    {
        $date = new \DateTime();
        return md5($date->format('c'));
    }
}
