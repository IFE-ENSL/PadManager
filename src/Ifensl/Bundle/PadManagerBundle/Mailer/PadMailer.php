<?php

/**
 *
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Ifensl\Bundle\PadManagerBundle\Mailer;

use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Symfony\Bundle\TwigBundle\TwigEngine;

class PadMailer
{
    protected $mailer;
    protected $twigEngine;

    /**
     * Constructor
     *
     * @param Swift_Mailer $mailer
     * @param TwigEngine $twigEngine
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $twigEngine)
    {
        $this->mailer = $mailer;
        $this->twigEngine = $twigEngine;
    }

    /**
     * Get Mailer
     *
     * @return Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }

    /**
     * Get Templating
     *
     * @return TwigEngine
     */
    protected function getTwigEngine()
    {
        return $this->twigEngine;
    }

}
