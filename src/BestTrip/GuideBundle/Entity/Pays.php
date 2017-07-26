<?php

namespace BestTrip\GuideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pays
 *
 * @ORM\Table(name="pays")
 * @ORM\Entity
 */
class Pays
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_rec", type="integer", nullable=true)
     */
    private $nbrRec = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_non_rec", type="integer", nullable=true)
     */
    private $nbrNonRec = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=true)
     */
    private $rating = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_rating", type="integer", nullable=true)
     */
    private $nbrRating = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\JoinTable(name="rating_pays")
     */
    private $usersRating;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\Commentaire")
     * @ORM\JoinTable(name="commentaire_pays")
     */
    private $commentaires;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Pays
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nbrRec
     *
     * @param integer $nbrRec
     * @return Pays
     */
    public function setNbrRec($nbrRec)
    {
        $this->nbrRec = $nbrRec;

        return $this;
    }

    /**
     * Get nbrRec
     *
     * @return integer
     */
    public function getNbrRec()
    {
        return $this->nbrRec;
    }

    /**
     * Set nbrNonRec
     *
     * @param integer $nbrNonRec
     * @return Pays
     */
    public function setNbrNonRec($nbrNonRec)
    {
        $this->nbrNonRec = $nbrNonRec;

        return $this;
    }

    /**
     * Get nbrNonRec
     *
     * @return integer
     */
    public function getNbrNonRec()
    {
        return $this->nbrNonRec;
    }

    /**
     * Set rating
     *
     * @param float $rating
     * @return Pays
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set nbrRating
     *
     * @param integer $nbrRating
     * @return Pays
     */
    public function setNbrRating($nbrRating)
    {
        $this->nbrRating = $nbrRating;

        return $this;
    }

    /**
     * Get nbrRating
     *
     * @return integer
     */
    public function getNbrRating()
    {
        return $this->nbrRating;
    }

    public function addCommentaire(\BestTrip\UserBundle\Entity\Commentaire $Commentaire)
    {
        $this->commentaires[] = $Commentaire;

        return $this;
    }

    /**
     * Remove Commentaire
     *
     * @param \BestTrip\UserBundle\Entity\Commentaire $Commentaire
     */
    public function removeCommentaire(\BestTrip\UserBundle\Entity\Commentaire $Commentaire)
    {
        $this->commentaires->removeElement($Commentaire);
    }

    /**
     * Get Commentaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add User
     *
     * @param \BestTrip\UserBundle\Entity\User $Commentaire
     */
    public function addRating(\BestTrip\UserBundle\Entity\User $user)
    {
        $this->usersRating[] = $user;

        return $this;
    }

    /**
     * Remove Commentaire
     *
     * @param \BestTrip\UserBundle\Entity\User $Commentaire
     */
    public function removeRating(\BestTrip\UserBundle\Entity\User $user)
    {
        $this->usersRating->removeElement($user);
    }

    /**
     * Get UserRating
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRatings()
    {
        return $this->usersRating;
    }

    function __toString()
    {
        return $this->getNom();
    }

}
