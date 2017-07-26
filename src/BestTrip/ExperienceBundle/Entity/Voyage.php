<?php

namespace BestTrip\ExperienceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voyage
 *
 * @ORM\Table(name="voyage")
 * @ORM\Entity
 */
class Voyage {

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
     * @ORM\Column(name="date_depart", type="string", length=255, nullable=true)
     */
    private $dateDepart;

    /**
     * @var string
     *
     * @ORM\Column(name="date_arrivee", type="string", length=255, nullable=true)
     */
    private $dateArrivee;

    /**
     * @var string
     *
     * @ORM\Column(name="saison", type="string", length=255, nullable=true)
     */
    private $saison;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="BestTrip\ExperienceBundle\Entity\Compagnie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="compagnie_de_voyage", referencedColumnName="id")
     * })
     *  */
    private $compagnieDeVoyage;

    /**
     * @var string
     *
     * @ORM\Column(name="moyen_de_transport", type="string", length=255, nullable=true)
     */
    private $moyenDeTransport;

    /**
     * @var \Experience
     *
     * @ORM\ManyToOne(targetEntity="BestTrip\ExperienceBundle\Entity\Experience")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_experience", referencedColumnName="id")
     * })
     */
    private $experience;

    /**
     * @var \Ville
     *
     * @ORM\ManyToOne(targetEntity="BestTrip\GuideBundle\Entity\Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ville_arrivee", referencedColumnName="id")
     * })
     */
    private $villeArrivee;

    /**
     * @var \Ville
     *
     * @ORM\ManyToOne(targetEntity="BestTrip\GuideBundle\Entity\Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ville_depart", referencedColumnName="id")
     * })
     */
    private $villeDepart;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="BestTrip\UserBundle\Entity\Commentaire")
     * @ORM\JoinTable(name="commentaire_voyage")
     */
    private $commentaires;

    /**
     * Constructor
     */
    public function __construct() {
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    function setId($id) {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dateDepart
     *
     * @param string $dateDepart
     * @return Voyage
     */
    public function setDateDepart($dateDepart) {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    /**
     * Get dateDepart
     *
     * @return string
     */
    public function getDateDepart() {
        return $this->dateDepart;
    }

    /**
     * Set dateArrivee
     *
     * @param string $dateArrivee
     * @return Voyage
     */
    public function setDateArrivee($dateArrivee) {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    /**
     * Get dateArrivee
     *
     * @return string
     */
    public function getDateArrivee() {
        return $this->dateArrivee;
    }

    /**
     * Set saison
     *
     * @param string $saison
     * @return Voyage
     */
    public function setSaison($saison) {
        $this->saison = $saison;

        return $this;
    }

    /**
     * Get saison
     *
     * @return string
     */
    public function getSaison() {
        return $this->saison;
    }

    /**
     * Set compagnieDeVoyage
     *
     * @param integer $compagnieDeVoyage
     * @return Voyage
     */
    public function setCompagnieDeVoyage($compagnieDeVoyage) {
        $this->compagnieDeVoyage = $compagnieDeVoyage;

        return $this;
    }

    /**
     * Get compagnieDeVoyage
     *
     * @return integer
     */
    public function getCompagnieDeVoyage() {
        return $this->compagnieDeVoyage;
    }

    /**
     * Set moyenDeTransport
     *
     * @param string $moyenDeTransport
     * @return Voyage
     */
    public function setMoyenDeTransport($moyenDeTransport) {
        $this->moyenDeTransport = $moyenDeTransport;

        return $this;
    }

    /**
     * Get moyenDeTransport
     *
     * @return string
     */
    public function getMoyenDeTransport() {
        return $this->moyenDeTransport;
    }

   
    public function setVilleArrivee($villeArrivee) {
        $this->villeArrivee = $villeArrivee;

        return $this;
    }

    
    public function getVilleArrivee() {
        return $this->villeArrivee;
    }

 
    public function setVilleDepart($villeDepart) {
        $this->villeDepart = $villeDepart;

        return $this;
    }

    
    public function getVilleDepart() {
        return $this->villeDepart;
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
     * @param \Experience $experience
     */
    public function setExperience($experience) {
        $this->experience = $experience;
    }

    /**
     * @return \Experience
     */
    public function getExperience() {
        return $this->experience;
    }

}
