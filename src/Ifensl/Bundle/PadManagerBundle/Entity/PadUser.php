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
 * Ifensl\Bundle\PadManagerBundle\Entity\PadUser
 *
 * @ORM\Entity
 * @ORM\Table(name="pad_user")
 */
class PadUser
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
     * @var string $name
     *
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     */
    private $email;

    /**
     * @var string $authorId
     *
     * @ORM\Column(type="string", length=128, nullable=true, unique=true)
     */
    private $authorId;

    /**
     * @var string $groupId
     *
     * @ORM\Column(type="string", length=128, nullable=true, unique=true)
     */
    private $groupId;

    /**
     * @ORM\OneToMany(targetEntity="Pad", mappedBy="padOwner")
     */
    private $ownPads;

    /**
     * @ORM\ManyToMany(targetEntity="Pad", mappedBy="padUsers")
     */
    private $pads;

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getEmail();
    }

    /**
     * Constructor
     *
     * @param string $email
     */
    public function __construct($email = null)
    {
        $this->email = $email;
        $this->pads = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set authorId
     *
     * @param string $authorId
     * @return PadUser
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    
        return $this;
    }

    /**
     * Get authorId
     *
     * @return string 
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * Set groupId
     *
     * @param string $groupId
     * @return PadUser
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    
        return $this;
    }

    /**
     * Get groupId
     *
     * @return string 
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return PadUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Add pads
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Pad $pad
     * @return PadUser
     */
    public function addPad(\Ifensl\Bundle\PadManagerBundle\Entity\Pad $pad)
    {
        $this->pads->add($pad);

        return $this;
    }

    /**
     * Remove pads
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Pad $pads
     */
    public function removePad(\Ifensl\Bundle\PadManagerBundle\Entity\Pad $pads)
    {
        $this->pads->removeElement($pads);
    }

    /**
     * Get pads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPads()
    {
        return $this->pads;
    }
 

    /**
     * Add ownPads
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Pad $ownPads
     * @return PadUser
     */
    public function addOwnPad(\Ifensl\Bundle\PadManagerBundle\Entity\Pad $ownPads)
    {
        $this->ownPads[] = $ownPads;
    
        return $this;
    }

    /**
     * Remove ownPads
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Pad $ownPads
     */
    public function removeOwnPad(\Ifensl\Bundle\PadManagerBundle\Entity\Pad $ownPads)
    {
        $this->ownPads->removeElement($ownPads);
    }

    /**
     * Get ownPads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOwnPads()
    {
        return $this->ownPads;
    }
}
