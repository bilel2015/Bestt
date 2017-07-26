<?php

namespace BestTrip\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of message
 * @ORM\Table(name="message")
 * @ORM\Entity
 */
class Message {

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
     * @ORM\Column(name="contenu", type="text", nullable=true)
     */
    private $contenu;

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_emetteur", referencedColumnName="id")
     * })
     */
    private $emetteur;

    /**
     * @ORM\ManyToOne(targetEntity="BestTrip\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_distinataire", referencedColumnName="id")
     * })
     */
    private $destinataire;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lu", type="boolean", nullable=false)
     */
    private $lu = '0';

    function __construct() {
        $date = new \DateTime("now");
    }
    
    function getId() {
        return $this->id;
    }

    function getContenu() {
        return $this->contenu;
    }

    function getEmetteur() {
        return $this->emetteur;
    }

    function getDestinataire() {
        return $this->destinataire;
    }

    function getDate() {
        return $this->date;
    }

    function getLu() {
        return $this->lu;
    }

    function setContenu($contenu) {
        $this->contenu = $contenu;
    }

    function setEmetteur($emetteur) {
        $this->emetteur = $emetteur;
    }

    function setDestinataire($destinataire) {
        $this->destinataire = $destinataire;
    }

    function setLu($lu) {
        $this->lu = $lu;
    }

    function setDate($date) {
        $this->date = $date;
    }



}
