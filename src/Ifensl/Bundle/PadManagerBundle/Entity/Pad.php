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
 * @ORM\Entity()
 * @ORM\Table(name="pad", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="pad", columns={
 *         "school_year",
 *         "program_id",
 *         "unit_id",
 *         "subject_id",
 *         "owner_id"
 *     })
 * })
 */
class Pad
{
    const STATE_NEW      = "NEW";
    const STATE_ENABLED  = "ENABLED";
    const STATE_DISABLED = "DISABLED";

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
     * @var integer $schoolYear
     *
     * @ORM\Column(name="school_year", type="integer")
     */
    private $schoolYear;

    /**
     * @var Program
     *
     * @ORM\ManyToOne(targetEntity="Program", inversedBy="pads")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $program;

    /**
     * @var Unit
     *
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="pads")
     * @ORM\JoinColumn(name="unit_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $unit;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="pads")
     * @ORM\JoinColumn(name="subject_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $subject;

    /**
     * @var PadUser
     *
     * @ORM\ManyToOne(targetEntity="PadUser", cascade={"all"}, inversedBy="ownPads")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $padOwner;

    /**
     * @var array<PadUser>
     *
     * @ORM\ManyToMany(targetEntity="PadUser", cascade={"all"}, inversedBy="pads")
     * @ORM\JoinTable(name="pad_pad_user",
     *     joinColumns={@ORM\JoinColumn(name="pad_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="pad_user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $padUsers;

    /**
     * toString
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%d %s %s %s',
            $this->getSchoolYear(),
            $this->getProgram(),
            $this->getUnit(),
            $this->getSubject()
        );
    }

    /**
     * Get States
     *
     * @return array
     */
    public static function getStates()
    {
        return array(
            self::STATE_NEW      => self::STATE_NEW,
            self::STATE_ENABLED  => self::STATE_ENABLED,
            self::STATE_DISABLED => self::STATE_DISABLED,
        );
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setState(self::STATE_NEW);
        $this->padUsers = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set schoolYear
     *
     * @param integer $schoolYear
     * @return Pad
     */
    public function setSchoolYear($schoolYear)
    {
        $this->schoolYear = $schoolYear;
    
        return $this;
    }

    /**
     * Get schoolYear
     *
     * @return integer 
     */
    public function getSchoolYear()
    {
        return $this->schoolYear;
    }

    /**
     * Set program
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Program $program
     * @return Pad
     */
    public function setProgram(\Ifensl\Bundle\PadManagerBundle\Entity\Program $program = null)
    {
        $this->program = $program;
    
        return $this;
    }

    /**
     * Get program
     *
     * @return \Ifensl\Bundle\PadManagerBundle\Entity\Program 
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set unit
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Unit $unit
     * @return Pad
     */
    public function setUnit(\Ifensl\Bundle\PadManagerBundle\Entity\Unit $unit = null)
    {
        $this->unit = $unit;
    
        return $this;
    }

    /**
     * Get unit
     *
     * @return \Ifensl\Bundle\PadManagerBundle\Entity\Unit 
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set subject
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Subject $subject
     * @return Pad
     */
    public function setSubject(\Ifensl\Bundle\PadManagerBundle\Entity\Subject $subject = null)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return \Ifensl\Bundle\PadManagerBundle\Entity\Subject 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set padOwner
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padOwner
     * @return Pad
     */
    public function setPadOwner(\Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padOwner = null)
    {
        $this->padOwner = $padOwner;
    
        return $this;
    }

    /**
     * Get padOwner
     *
     * @return \Ifensl\Bundle\PadManagerBundle\Entity\PadUser 
     */
    public function getPadOwner()
    {
        return $this->padOwner;
    }

    /**
     * Add padUser
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUser
     * @return Pad
     */
    public function addPadUser(\Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUser)
    {
        $this->padUsers[] = $padUser;

        return $this;
    }

    /**
     * Remove padUser
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUser
     */
    public function removePadUser(\Ifensl\Bundle\PadManagerBundle\Entity\PadUser $padUser)
    {
        $this->padUsers->removeElement($padUser);
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
