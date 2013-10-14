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
     * @var array<PadUser>
     *
     * @ORM\ManyToMany(targetEntity="PadUser", cascade={"all"})
     * @ORM\JoinTable(name="pad_paduser",
     *     joinColumns={@ORM\JoinColumn(name="pad_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $padUsers;

    /**
     * Get pad user owner
     *
     * @return PadUser 
     */
    public function getOwner()
    {
        return $this->padUsers[0];
    }

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * @param string $schoolYear
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
     * @return string 
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
