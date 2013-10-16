<?php

/**
 *
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Ifensl\Bundle\PadManagerBundle;

use Doctrine\ORM\EntityManager;
use Ifensl\Bundle\PadManagerBundle\TokenGenerator\PadTokenGenerator;
use Ifensl\Bundle\PadManagerBundle\Mailer\PadMailer;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Entity\PadUser;

class PadManager
{
    protected $entityManager;
    protected $tokenGenerator;
    protected $mailer;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param PadTokenGenerator $tokenGenerator
     * @param PadMailer $mailer
     */
    public function __construct(EntityManager $entityManager, PadTokenGenerator $tokenGenerator, PadMailer $mailer)
    {
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    /**
     * Get EntityManager
     *
     * @param EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Get TokenGenerator
     *
     * @param PadTokenGenerator
     */
    protected function getTokenGenerator()
    {
        return $this->tokenGenerator;
    }

    /**
     * Get Mailer
     *
     * @param PadMailer
     */
    protected function getMailer()
    {
        return $this->mailer;
    }

    /**
     * Generate pad tokens
     *
     * @param Pad $pad
     */
    protected function generatePadTokens(Pad & $pad)
    {
        $salt = $this->getTokenGenerator()->generateSalt();
        $pad->setSalt($salt);
        $pad->setPrivateToken($this->getTokenGenerator()->generateToken($pad, 'private', $salt));
        $pad->setPublicToken($this->getTokenGenerator()->generateToken($pad, 'public', $salt));
    }

    /**
     * Get current schoolYear
     * 
     * @return integer
     */
    public function getCurrentSchoolYear()
    {
        $date = new \DateTime('now');
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));

        if ($month <= 8) {
            return $year-1;
        } else {
            return $year;
        }
    }

    /**
     * Set current schoolYear
     * 
     * @param Pad $pad
     */
    public function setCurrentSchoolYear(Pad $pad)
    {
        $pad->setSchoolYear($this->getCurrentSchoolYear());
    }

    /**
     * Create a Pad
     *
     * @param Pad $pad
     */
    public function createPad(Pad $pad)
    {
        $this->generatePadTokens($pad);
        $this->setCurrentSchoolYear($pad);
        $this->getEntityManager()->persist($pad);
        $this->getEntityManager()->flush();
        $this->getMailer()->sendPadCreationMail($pad);
    }

    /**
     * Invite Pad User
     *
     * @param Pad $pad
     * @param PadUser $user
     */
    public function invitePadUser(Pad $pad, PadUser $user)
    {
        $this->getMailer()->sendInvitationMail($pad, $user);
    }

    /**
     * Remove Pad User
     *
     * @param Pad $pad
     * @param PadUser $user
     */
    public function removePadUser(Pad $pad, PadUser $user)
    {
        
    }

    /**
     * Send Pad Token to a Pad User
     *
     * @param Pad $pad
     * @param PadUser $user
     */
    public function sendToUserPadToken(Pad $pad, PadUser $user)
    {
        $this->getMailer()->sendTokenMail($pad, $user);
    }
}
