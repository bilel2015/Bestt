<?php

namespace BestTrip\ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecCompagnie
 *
 * @ORM\Table(name="rec_compagnie")
 * @ORM\Entity
 */
class RecCompagnie
{
    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\ExperienceBundle\Entity\Compagnie")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $compagnie;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=true)
     */
    private $state = '0';
    function getUser() {
        return $this->user;
    }

    function getCompagnie() {
        return $this->compagnie;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function setCompagnie($compagnie) {
        $this->compagnie = $compagnie;
    }

        /**
     * Set state
     *
     * @param integer $state
     * @return RecCompagnie
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
