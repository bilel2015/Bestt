<?php

namespace BestTrip\GuideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecGuide
 *
 * @ORM\Table(name="rec_guide")
 * @ORM\Entity
 */
class RecGuide
{
    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\GuideBundle\Entity\Guide")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $guide;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=true)
     */
    private $state = '0';

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
     * @return RecGuide
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
