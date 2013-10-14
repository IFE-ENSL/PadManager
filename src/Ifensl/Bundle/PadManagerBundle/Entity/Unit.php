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
 * Ifensl\Bundle\PadManagerBundle\Entity\Unit
 *
 * @ORM\Entity
 * @ORM\Table(name="pad_unit")
 */
class Unit
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
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Pad", mappedBy="unit")
     */
    private $pads;

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

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
     * Set name
     *
     * @param string $name
     * @return Unit
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Unit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add pad
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Pad $pad
     * @return Unit
     */
    public function addPad(\Ifensl\Bundle\PadManagerBundle\Entity\Pad $pad)
    {
        $this->pads[] = $pad;

        return $this;
    }

    /**
     * Remove pad
     *
     * @param \Ifensl\Bundle\PadManagerBundle\Entity\Pad $pad
     */
    public function removePad(\Ifensl\Bundle\PadManagerBundle\Entity\Pad $pad)
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
