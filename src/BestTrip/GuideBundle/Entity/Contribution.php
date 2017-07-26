<?php

namespace BestTrip\GuideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contribution
 *
 * @ORM\Table(name="contribution")
 * @ORM\Entity
 */
class Contribution
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="text")
     */
    private $descr;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateContribution", type="datetime")
     */
    private $dateContribution;

    /**
     * @var string
     *
     * @ORM\Column(name="autreInfo", type="text")
     */
    private $autreInfo;

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\GuideBundle\Entity\Guide")
     */
    private $guide;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", nullable=true)
     */
    private $valid = '0';

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Ville")
     */
    private $villes;

    /**
     * @ORM\ManyToMany(targetEntity="Lieu")
     */
    private $lieux;

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
     * @param mixed $guide
     */
    public function setGuide($guide)
    {
        $this->guide = $guide;
    }

    /**
     * @return mixed
     */
    public function getGuide()
    {
        return $this->guide;
    }

    /**
     * Set descr
     *
     * @param string $descr
     * @return Contribution
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string 
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set dateContribution
     *
     * @param \DateTime $dateContribution
     * @return Contribution
     */
    public function setDateContribution($dateContribution)
    {
        $this->dateContribution = $dateContribution;

        return $this;
    }

    /**
     * Get dateContribution
     *
     * @return \DateTime 
     */
    public function getDateContribution()
    {
        return $this->dateContribution;
    }

    /**
     * Set autreInfo
     *
     * @param string $autreInfo
     * @return Contribution
     */
    public function setAutreInfo($autreInfo)
    {
        $this->autreInfo = $autreInfo;

        return $this;
    }

    /**
     * Get autreInfo
     *
     * @return string 
     */
    public function getAutreInfo()
    {
        return $this->autreInfo;
    }

    public function addVilles(Ville $ville)
    {
        $this->villes[] = $ville;

        return $this;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param boolean $valid
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
    }

    /**
     * @return boolean
     */
    public function getValid()
    {
        return $this->valid;
    }

    public function removeVille(Ville $ville)
    {
        $this->villes->removeElement($ville);
    }

    public function getVilles()
    {
        return $this->villes;
    }

    public function addLieux(Lieu $lieu)
    {
        $this->lieux[] = $lieu;

        return $this;
    }

    public function removeLieu(Lieu $lieu)
    {
        $this->lieux->removeElement($lieu);
    }

    public function getLieux()
    {
        return $this->lieux;
    }
}
