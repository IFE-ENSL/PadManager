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
use Doctrine\Common\Collections\ArrayCollection;
use Ifensl\Bundle\PadManagerBundle\TokenGenerator\PadTokenGenerator;
use Ifensl\Bundle\PadManagerBundle\Mailer\PadMailer;
use Ifensl\Bundle\PadManagerBundle\Entity\Pad;
use Ifensl\Bundle\PadManagerBundle\Entity\PadUser;
use Ifensl\Bundle\PadManagerBundle\Entity\Program;
use Ifensl\Bundle\PadManagerBundle\Entity\Subject;
use Ifensl\Bundle\PadManagerBundle\Entity\Unit;
use Ifensl\Bundle\PadManagerBundle\Exception\PadAlreadyExistException;

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
        }

        return $year;
    }

    /**
     * Create a Pad
     *
     * @param PadUser $owner
     * @param Program $program
     * @param Unit $unit
     * @param Subject $subject
     * @return Pad $pad
     */
    public function createPad(PadUser $owner, Program $program, Unit $unit, Subject $subject)
    {
        $pad = $this
            ->getEntityManager()
            ->getRepository("IfenslPadManagerBundle:Pad")
            ->findOneBy(array(
                'padOwner'    => $owner->getId(),
                'program'  => $program->getId(),
                'unit'     => $unit->getId(),
                'subject'  => $subject->getId()
            ))
        ;

        if ($pad) {
            throw new PadAlreadyExistException($pad);
        }

        $pad = new Pad();
        $pad
            ->setPadOwner($owner)
            ->setSchoolYear($this->getCurrentSchoolYear())
            ->setProgram($program)
            ->setUnit($unit)
            ->setSubject($subject)
        ;
        $this->generatePadTokens($pad);
        $this->getEntityManager()->persist($pad);
        $this->getEntityManager()->flush();
        //$this->getMailer()->sendOwnerMail($pad, $user);

        return $pad;
    }

    /**
     * Invite Pad User
     *
     * @param Pad $pad
     * @param PadUser $user
     */
    public function invitePadUser(Pad $pad, PadUser $user)
    {
        $pad->addPadUser($user);
        $this->getEntityManager()->persist($pad);
        $this->getEntityManager()->flush();

        //$this->getMailer()->sendInvitationMail($pad, $user);
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
}
