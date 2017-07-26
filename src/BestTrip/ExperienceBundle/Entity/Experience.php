<?php

namespace BestTrip\ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Experience
 *
 * @ORM\Table(name="experience", indexes={@ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Experience {

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
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="date_ajout", type="string", length=255, nullable=true)
     */
    private $dateAjout;

    /**
     * @var string
     *
     * @ORM\Column(name="date_derniere_modification", type="string", length=255, nullable=true)
     */
    private $dateDerniereModification;

    /**
     * @var float
     *
     * @ORM\Column(name="depenses", type="float", precision=10, scale=0, nullable=true)
     */
    private $depenses = '0';

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="valid", type="boolean", nullable=false)
     */
    private $valid = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_rating", type="integer", nullable=false)
     */
    private $nbrRating = '0';

    /**
     * @ORM\OneToOne(targetEntity="BestTrip\SiteBundle\Entity\Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_image", referencedColumnName="id")
     * })
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=true)
     */
    private $rating = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\JoinTable(name="rating_experience")
     */
    private $usersRating;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\Commentaire")
     * @ORM\JoinTable(name="commentaire_experience")
     */
    private $commentaires;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Experience
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Experience
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set dateAjout
     *
     * @param string $dateAjout
     * @return Experience
     */
    public function setDateAjout($dateAjout) {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return string
     */
    public function getDateAjout() {
        return $this->dateAjout;
    }

    /**
     * Set dateDerniereModification
     *
     * @param string $dateDerniereModification
     * @return Experience
     */
    public function setDateDerniereModification($dateDerniereModification) {
        $this->dateDerniereModification = $dateDerniereModification;

        return $this;
    }

    /**
     * Get dateDerniereModification
     *
     * @return string
     */
    public function getDateDerniereModification() {
        return $this->dateDerniereModification;
    }

    /**
     * Set depenses
     *
     * @param float $depenses
     * @return Experience
     */
    public function setDepenses($depenses) {
        $this->depenses = $depenses;

        return $this;
    }

    /**
     * Get depenses
     *
     * @return float
     */
    public function getDepenses() {
        return $this->depenses;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     * @return Experience
     */
    public function setValid($valid) {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean
     */
    public function getValid() {
        return $this->valid;
    }

    /**
     * Set nbrRating
     *
     * @param integer $nbrRating
     * @return Experience
     */
    public function setNbrRating($nbrRating) {
        $this->nbrRating = $nbrRating;

        return $this;
    }

    /**
     * Get nbrRating
     *
     * @return integer
     */
    public function getNbrRating() {
        return $this->nbrRating;
    }

    /**
     * Set idImage
     *
     * @param integer $idImage
     * @return Experience
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get idImage
     *
     * @return integer
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set rating
     *
     * @param float $rating
     * @return Experience
     */
    public function setRating($rating) {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating() {
        return $this->rating;
    }

    public function addCommentaire(\BestTrip\UserBundle\Entity\Commentaire $Commentaire) {
        $this->commentaires[] = $Commentaire;

        return $this;
    }

    /**
     * Remove Commentaire
     *
     * @param \BestTrip\UserBundle\Entity\Commentaire $Commentaire
     */
    public function removeCommentaire(\BestTrip\UserBundle\Entity\Commentaire $Commentaire) {
        $this->commentaires->removeElement($Commentaire);
    }

    /**
     * Get Commentaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires() {
        return $this->commentaires;
    }

    /**
     * Add User
     *
     * @param \BestTrip\UserBundle\Entity\User $Commentaire
     */
    public function addRating(\BestTrip\UserBundle\Entity\User $user) {
        $this->usersRating[] = $user;

        return $this;
    }

    /**
     * Remove Commentaire
     *
     * @param \BestTrip\UserBundle\Entity\User $Commentaire
     */
    public function removeRating(\BestTrip\UserBundle\Entity\User $user) {
        $this->usersRating->removeElement($user);
    }

    /**
     * Get UserRating
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRatings() {
        return $this->usersRating;
    }

}
