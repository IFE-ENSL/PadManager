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
use Ifensl\Bundle\PadManagerBundle\Etherpad\EtherpadManager;

class PadMailer
{
    protected $mailer;
    protected $twigEngine;
    protected $etherPadManager;
    protected $mailerConfiguration;

    /**
     * Constructor
     *
     * @param Swift_Mailer $mailer
     * @param TwigEngine $twigEngine
     * @param array $mailerConfiguration
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $twigEngine, $mailerConfiguration)
    {
        $this->mailer = $mailer;
        $this->twigEngine = $twigEngine;
        $this->mailerConfiguration = $mailerConfiguration;
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
     * Get Mailer configuration
     *
     * @param string | null $key
     * @return mixed | null
     */
    protected function getMailerConfiguration($key = null)
    {
        if (null === $key) {
            return $this->mailerConfiguration;
        }

        if (isset($this->mailerConfiguration[$key])) {
            return $this->mailerConfiguration[$key];
        }

        return null;
    }

    /**
     * Send created mail
     * 
     * @param Pad $pad
     * @param PadUser $user
     */
    public function sendCreatedMail(Pad $pad)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->getMailerConfiguration('from'))
            ->setSubject('Un nouveau pad à été créer')
            ->setTo($pad->getPadOwner()->getEmail())
            ->setBody($this->getTwigEngine()
                ->render('IfenslPadManagerBundle:Mail:created.txt.twig',
                    array('pad' => $pad)
                )
            )
        ;

        $this->getMailer()->send($message);
    }

    /**
     * Send invited mail
     * 
     * @param Pad $pad
     * @param PadUser $user
     */
    public function sendInvitedMail(Pad $pad, PadUser $user)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->getMailerConfiguration('from'))
            ->setSubject('Vous avez été invité à rejoindre un pad')
            ->setTo($user->getEmail())
            ->setBody($this->getTwigEngine()
                ->render('IfenslPadManagerBundle:Mail:invited.txt.twig',
                    array('pad' => $pad)
                )
            )
        ;

        $this->getMailer()->send($message);
    }

    /**
     * Send lost mail
     * 
     * @param Pad $pad
     */
    public function sendLostMail(Pad $pad)
    {
        $message = \Swift_Message::newInstance()
            ->setFrom($this->getMailerConfiguration('from'))
            ->setSubject('Récupération des identifiants')
            ->setTo($pad->getPadOwner()->getEmail())
            ->setBody($this->getTwigEngine()
                ->render('IfenslPadManagerBundle:Mail:lost.txt.twig',
                    array('pad' => $pad)
                )
            )
        ;

        $this->getMailer()->send($message);
    }
}

