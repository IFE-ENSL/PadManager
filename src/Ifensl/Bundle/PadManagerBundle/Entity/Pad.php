<?php

/**
 *
 * @author:  Baptiste BOUCHEREAU <baptiste.bouchereau@idci-consulting.fr>
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Ifensl\Bundle\PadManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ifensl\Bundle\PadManagerBundle\Entity\Pad
 *
 * @ORM\Table(name="pad")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Pad
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $privateToken
     *
     * @ORM\Column(name="private_token", type="string", length=255)
     */
    private $privateToken;

    /**
     * @var string $publicToken
     *
     * @ORM\Column(name="public_token", type="string", length=255)
     */
    private $publicToken;

    /**
     * @var string $ue
     *
     * @ORM\Column(type="string", length=255)
     */
    private $ue;

    /**
     * @var string $subject
     *
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @var string $program
     *
     * @ORM\Column(type="string", length=255)
     */
    private $program;

    /**
     * @var string $state
     *
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @var string $salt
     *
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @var string $schoolYear
     *
     * @ORM\Column(name="school_year", type="string", length=255)
     */
    private $schoolYear;

    /**
     * @ORM\ManyToMany(targetEntity="PadUser", inversedBy="pads")
     * @ORM\JoinColumn(nullable=false)

     */
    private $padUsers;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->padUsers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->salt = "z@6X(!{+fKR}*^x0m||phK8p@I]-|_M5==Z(qh2X n<};inA!;oUEt~caO9Z/2W`";
        $this->setSchoolYear();
    }

    /**
     * Set tokens
     *
     * @ORM\PrePersist()
     */
    public function generateTokens()
    {
        $privateToken = sprintf("%s%s%ss%s%s",
            $this->salt,
            $this->ue,
            $this->program,
            $this->subject,
            $this->getPadUserOwner()[0]
        );

        $publicToken = "TODO";

        $this->privateToken = md5($privateToken);
        $this->publicToken = md5($publicToken);

        return $this;
    }

    /**
     * Get pad user owner
     *
     * @return PadUser 
     */
    public function getPadUserOwner()
    {
        return $this->padUsers[0];
    }

    /**
     * Set schoolYear
     *
     * @param string $schoolYear
     * @return Pad
     */
    public function setSchoolYear()
    {
        $date = new \DateTime("now");
        $month = intval($date->format('m'));
        $year = intval($date->format('Y'));

        if ($month >= 9) {
            $this->schoolYear = sprintf("%s-%s", $year, $year+1);
        } else {
            $this->schoolYear = sprintf("%s-%s", $year-1, $year );
        }

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set privateToken
     *
     * @param string $privateToken
     * @return Pad
     */
    public function setPrivateToken($privateToken)
    {
        $this->privateToken = $privateToken;
    
        return $this;
    }

    /**
     * Get privateToken
     *
     * @return string 
     */
    public function getPrivateToken()
    {
        return $this->privateToken;
    }

    /**
     * Set publicToken
     *
     * @param string $publicToken
     * @return Pad
     */
    public function setPublicToken($publicToken)
    {
        $this->publicToken = $publicToken;
    
        return $this;
    }

    /**
     * Get publicToken
     *
     * @return string 
     */
    public function getPublicToken()
    {
        return $this->publicToken;
    }

    /**
     * Set ue
     *
     * @param string $ue
     * @return Pad
     */
    public function setUe($ue)
    {
        $this->ue = $ue;
    
        return $this;
    }

    /**
     * Get ue
     *
     * @return string 
     */
    public function getUe()
    {
        return $this->ue;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Pad
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set program
     *
     * @param string $program
     * @return Pad
     */
    public function setProgram($program)
    {
        $this->program = $program;
    
        return $this;
    }

    /**
     * Get program
     *
     * @return string 
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Pad
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Pad
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Get schoolYear
     *
     * @return string 
     */
    public function getSchoolYear()
    {
        return $this->schoolYear;
    }

    /**
     * Add padUsers
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUsers
     * @return Pad
     */
    public function addPadUser(\Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUsers)
    {
        $this->padUsers[] = $padUsers;
    
        return $this;
    }

    /**
     * Remove padUsers
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUsers
     */
    public function removePadUser(\Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUsers)
    {
        $this->padUsers->removeElement($padUsers);
    }

    /**
     * Get padUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPadUsers()
    {
        return $this->padUsers;
    }
}