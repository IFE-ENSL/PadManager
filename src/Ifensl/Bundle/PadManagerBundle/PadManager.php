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
use Da\ApiClientBundle\HttpClient\RestApiClientBridge;
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
    protected $etherpadApiClient;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param PadTokenGenerator $tokenGenerator
     * @param PadMailer $mailer
     */
    public function __construct(EntityManager $entityManager, PadTokenGenerator $tokenGenerator, PadMailer $mailer, RestApiClientBridge $etherpadApiClient)
    {
        $this->entityManager     = $entityManager;
        $this->tokenGenerator    = $tokenGenerator;
        $this->mailer            = $mailer;
        $this->etherpadApiClient = $etherpadApiClient;
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
     * Get Repository
     *
     * @return \Doctrine\ORM\EntityManager\EntityRepository
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository("IfenslPadManagerBundle:Pad");
    }

    /**
     * Magic call
     * Triger to repository methods call
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->getRepository(), $method), $args);
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
     * Get therpadApiClient
     *
     * @return RestApiClientBridge
     */
    protected function getEtherpadApiClient()
    {
        return $this->etherpadApiClient;
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

    public function createOwnerSession()
    {
        
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
        $pad = $this->findOneBy(array(
            'padOwner' => $owner->getId(),
            'program'  => $program->getId(),
            'unit'     => $unit->getId(),
            'subject'  => $subject->getId()
        ));

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

        $this->getEtherpadApiClient()->createNewPad($pad);

        $this->getEntityManager()->persist($pad);
        $this->getEntityManager()->flush();

        $this->sendOwnerMail($pad);

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
        $this->sendInvitationMail($pad, $user);
    }

    /**
     * Send owner mail
     *
     * @param Pad $pad 
     */
    public function sendOwnerMail(Pad $pad)
    {
        $this->getMailer()->sendCreatedMail($pad);
    }

    /**
     * Send PadUser invitation mail
     *
     * @param Pad $pad
     * @param PadUser $user
     */
    public function sendInvitationMail(Pad $pad, PadUser $user)
    {
        $this->getMailer()->sendInvitedMail($pad, $user);
    }

    /**
     * Send link lost mail
     *
     * @param Pad $pad 
     */
    public function sendLinkLostMail(Pad $pad)
    {
        $this->getMailer()->sendLostMail($pad);
    }

    /**
     * Remove Pad User
     *
     * @param Pad $pad
     * @param PadUser $user
     */
    public function removePadUser(Pad $pad, PadUser $user)
    {
        die('todo');
    }
}
