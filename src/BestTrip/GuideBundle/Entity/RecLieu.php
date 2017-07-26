<?php

namespace BestTrip\GuideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecLieu
 *
 * @ORM\Table(name="rec_lieu")
 * @ORM\Entity
 */
class RecLieu
{
    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\GuideBundle\Entity\Lieu")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $lieu;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=true)
     */
    private $state = '0';

    /**
     * @param mixed $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * @return mixed
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return RecLieu
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer
     */
    public function getState()
    {
        return $this->state;
    }
}
