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
use Ifensl\Bundle\PadManagerBundle\Entity\PadUser;
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

    /**
     * Send Token Mail
     * 
     * @param Pad $pad
     */
    public function sendTokenMail(Pad $pad, PadUser $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Récupération des identifiants')
            ->setTo($user->getEmail())
            ->setBody($this
                ->getTwigEngine()
                ->renderView('IfenslPadManagerBundle:Mail:token.txt.twig',
                    array('pad' => $pad)
                )
            )
        ;

        $this->getMailer()->send($message);
    }

    /**
     * Send Invitation Mail
     * 
     * @param Pad $pad
     * @param PadUser $user
     */
    public function sendInvitationMail(Pad $pad, PadUser $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Vous avez été invité à rejoindre un pad')
            ->setTo($user->getEmail())
            ->setBody($this
                ->getTwigEngine()
                ->renderView('IfenslPadManagerBundle:Mail:invite.txt.twig',
                    array('pad' => $pad)
                )
            )
        ;

        $this->getMailer()->send($message);
    }

    /**
     * Send Pad Creation Mail
     * 
     * @param Pad $pad
     * @param PadUser $user
     */
    public function sendPadCreationMail(Pad $pad)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Un nouveau pad à été créer')
            ->setTo($pad->getOwner()->getEmail())
            ->setBody($this
                ->getTwigEngine()
                ->renderView('IfenslPadManagerBundle:Mail:padCreation.txt.twig',
                    array('pad' => $pad)
                )
            )
        ;

        $this->getMailer()->send($message);
    }
}

            