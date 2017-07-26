<?php

namespace BestTrip\GuideBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lieu
 *
 * @ORM\Table(name="lieu", indexes={@ORM\Index(name="fk_ville", columns={"id_ville"})})
 * @ORM\Entity
 */
class Lieu
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
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", precision=10, scale=0, nullable=true)
     */
    private $rating = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="histoire", type="string", length=255, nullable=true)
     */
    private $histoire;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombre_etoiles", type="integer", nullable=true)
     */
    private $nombreEtoiles = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="specialite", type="string", length=255, nullable=true)
     */
    private $specialite;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_hotel", type="boolean", nullable=true)
     */
    private $isHotel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_resto", type="boolean", nullable=true)
     */
    private $isResto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_monument", type="boolean", nullable=true)
     */
    private $isMonument;

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
     * @var integer
     *
     * @ORM\Column(name="nbr_rating", type="integer", nullable=true)
     */
    private $nbrRating = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\JoinTable(name="rating_lieu")
     */
    private $usersRating;

    /**
     * @var Ville
     *
     * @ORM\ManyToOne(targetEntity="Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ville", referencedColumnName="id")
     * })
     */
    private $ville;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\Commentaire")
     * @ORM\JoinTable(name="commentaire_lieu")
     */
    private $commentaires;

    /**
     * @ORM\OneToOne(targetEntity="BestTrip\SiteBundle\Entity\Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_image", referencedColumnName="id")
     * })
     */
    private $image;

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
     * Set adresse
     *
     * @param string $adresse
     * @return Lieu
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Lieu
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
     * Set nom
     *
     * @param string $nom
     * @return Lieu
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
     * Set rating
     *
     * @param float $rating
     * @return Lieu
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
     * Set histoire
     *
     * @param string $histoire
     * @return Lieu
     */
    public function setHistoire($histoire)
    {
        $this->histoire = $histoire;

        return $this;
    }

    /**
     * Get histoire
     *
     * @return string
     */
    public function getHistoire()
    {
        return $this->histoire;
    }

    /**
     * Set nombreEtoiles
     *
     * @param integer $nombreEtoiles
     * @return Lieu
     */
    public function setNombreEtoiles($nombreEtoiles)
    {
        $this->nombreEtoiles = $nombreEtoiles;

        return $this;
    }

    /**
     * Get nombreEtoiles
     *
     * @return integer
     */
    public function getNombreEtoiles()
    {
        return $this->nombreEtoiles;
    }

    /**
     * Set specialite
     *
     * @param string $specialite
     * @return Lieu
     */
    public function setSpecialite($specialite)
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * Get specialite
     *
     * @return string
     */
    public function getSpecialite()
    {
        return $this->specialite;
    }

    /**
     * Set isHotel
     *
     * @param boolean $isHotel
     * @return Lieu
     */
    public function setIsHotel($isHotel)
    {
        $this->isHotel = $isHotel;

        return $this;
    }

    /**
     * Get isHotel
     *
     * @return boolean
     */
    public function getIsHotel()
    {
        return $this->isHotel;
    }

    /**
     * Set isResto
     *
     * @param boolean $isResto
     * @return Lieu
     */
    public function setIsResto($isResto)
    {
        $this->isResto = $isResto;

        return $this;
    }

    /**
     * Get isResto
     *
     * @return boolean
     */
    public function getIsResto()
    {
        return $this->isResto;
    }

    /**
     * Set isMonument
     *
     * @param boolean $isMonument
     * @return Lieu
     */
    public function setIsMonument($isMonument)
    {
        $this->isMonument = $isMonument;

        return $this;
    }

    /**
     * Get isMonument
     *
     * @return boolean
     */
    public function getIsMonument()
    {
        return $this->isMonument;
    }

    /**
     * Set nbrRec
     *
     * @param integer $nbrRec
     * @return Lieu
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
     * @return Lieu
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
     * Set nbrRating
     *
     * @param integer $nbrRating
     * @return Lieu
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

    /**
     * @param \Ville $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return \Ville
     */
    public function getVille()
    {
        return $this->ville;
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

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

}
