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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Pad", mappedBy="padUsers")
     */
    private $pads;

    /**
     * Constructor
     */
    public function __construct()
    {
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
        if (!$this->pads->contains($pad)) {
            $this->pads->add($pad);
        }

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
 
}
