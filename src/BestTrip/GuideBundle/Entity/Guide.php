<?php

namespace BestTrip\GuideBundle\Entity;

use BestTrip\UserBundle\Entity\Commentaire;
use BestTrip\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Guide
 *
 * @ORM\Table(name="guide", indexes={@ORM\Index(name="fk_pays", columns={"id_pays"})})
 * @ORM\Entity(repositoryClass="BestTrip\GuideBundle\Entity\GuideRepository")
 */
class Guide
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
     * @ORM\Column(name="date_Creation", type="string", length=255, nullable=true)
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="date_Dernier_Contribution", type="string", length=255, nullable=true)
     */
    private $dateDernierContribution;

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
     * @ORM\ManyToMany(targetEntity="BestTrip\GuideBundle\Entity\Contribution")
     * @ORM\JoinTable(name="contribution_guide")
     */
    private $contributions;

    /**
     * @ORM\OneToOne(targetEntity="BestTrip\SiteBundle\Entity\Image")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_image", referencedColumnName="id")
     * })
     */
    private $image;

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
     * @ORM\JoinTable(name="rating_guide")
     */
    private $usersRating;

    /**
     * @var Pays
     *
     * @ORM\ManyToOne(targetEntity="Pays")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_pays", referencedColumnName="id")
     * })
     */
    private $pays;

    /**
     * @ORM\ManyToMany(targetEntity="Ville")
     */
    private $villes;

    /**
     * @ORM\ManyToMany(targetEntity="Lieu")
     */
    private $lieux;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\Commentaire")
     * @ORM\JoinTable(name="commentaire_guide")
     */
    private $commentaires;

    /**
     * @var string
     *
     * @ORM\Column(name="autres", type="text", nullable=true)
     */
    private $autres;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contributeurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usersRating = new \Doctrine\Common\Collections\ArrayCollection();
        $this->villes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lieux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return string
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param string $dateDernierContribution
     */
    public function setDateDernierContribution($dateDernierContribution)
    {
        $this->dateDernierContribution = $dateDernierContribution;
    }

    /**
     * @return string
     */
    public function getDateDernierContribution()
    {
        return $this->dateDernierContribution;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param int $nbrNonRec
     */
    public function setNbrNonRec($nbrNonRec)
    {
        $this->nbrNonRec = $nbrNonRec;
    }

    /**
     * @return int
     */
    public function getNbrNonRec()
    {
        return $this->nbrNonRec;
    }

    /**
     * @param int $nbrRating
     */
    public function setNbrRating($nbrRating)
    {
        $this->nbrRating = $nbrRating;
    }

    /**
     * @return int
     */
    public function getNbrRating()
    {
        return $this->nbrRating;
    }

    /**
     * @param int $nbrRec
     */
    public function setNbrRec($nbrRec)
    {
        $this->nbrRec = $nbrRec;
    }

    /**
     * @return int
     */
    public function getNbrRec()
    {
        return $this->nbrRec;
    }

    /**
     * @param \Pays $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return \Pays
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param float $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param string $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
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

    /**
     * @param string $autres
     */
    public function setAutres($autres)
    {
        $this->autres = $autres;
    }

    /**
     * @return string
     */
    public function getAutres()
    {
        return $this->autres;
    }

    public function addCommentaire(Commentaire $Commentaire)
    {
        $this->commentaires[] = $Commentaire;

        return $this;
    }

    /**
     * Remove Commentaire
     *
     * @param Commentaire $Commentaire
     */
    public function removeCommentaire(Commentaire $Commentaire)
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


    public function addRating(User $user)
    {
        $this->usersRating[] = $user;

        return $this;
    }


    public function removeRating(User $user)
    {
        $this->usersRating->removeElement($user);
    }

    public function getRatings()
    {
        return $this->usersRating;
    }

    public function addContribution(Contribution $contribution)
    {
        $this->contributions[] = $contribution;

        return $this;
    }

    public function removeContribution(Contribution $contribution)
    {
        $this->contributions->removeElement($contribution);
    }

    public function getContributions()
    {
        return $this->contributions;
    }

    public function addVilles(Ville $ville)
    {
        $this->villes[] = $ville;

        return $this;
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
