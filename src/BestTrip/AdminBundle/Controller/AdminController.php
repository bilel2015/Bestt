<?php

namespace BestTrip\AdminBundle\Controller;

use BestTrip\ExperienceBundle\Entity\Experience;
use BestTrip\GuideBundle\Controller\LieuController;
use BestTrip\GuideBundle\Entity\Guide;
use BestTrip\GuideBundle\Entity\Lieu;
use BestTrip\UserBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function listGuidesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $guides = $em->getRepository('GuideBundle:Guide')->findAll();
        return $this->render('AdminBundle:Admin:listGuides.html.twig', array(
            'guides' => $guides
        ));
    }

    public function validerGuideAction(Guide $guide, $valid)
    {
        $em = $this->getDoctrine()->getManager();

        if ($valid > 0) {
            $guide->setValid(true);
        } else
            $guide->setValid(false);

        $em->flush();

        return $this->redirect($this->generateUrl('guide_show', array('id' => $guide->getId())));
    }

    public function listExperienceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $experiences = $em->getRepository('ExperienceBundle:Experience')->findAll();

        return $this->render('AdminBundle:Admin:listExperience.html.twig', array( // ...
            'experiences' => $experiences
        ));
    }

    public function validerExpAction(Experience $experience, $valid)
    {
        $em = $this->getDoctrine()->getManager();

        if ($valid > 0) {
            $experience->setValid(true);
        } else
            $experience->setValid(false);

        $em->flush();

        return $this->redirect($this->generateUrl('experience_show', array('id' => $experience->getId())));
    }

    public function listLieuxAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lieux = $em->getRepository('GuideBundle:Lieu')->findAll();

        return $this->render('AdminBundle:Admin:listLieux.html.twig', array( // ...
            'lieux' => $lieux
        ));
    }

    public function validerLieuAction(Lieu $lieu, $valid)
    {
        $em = $this->getDoctrine()->getManager();

        if ($valid > 0) {
            $lieu->setValid(true);
        } else
            $lieu->setValid(false);

        $em->flush();

        return $this->redirect($this->generateUrl('lieu_show', array('id' => $lieu->getId())));
    }

    public function listCompagniesAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ExperienceBundle:Compagnie')->findAll();


        return $this->render('AdminBundle:Admin:listCompagnies.html.twig', array( // ...
            'entities' => $entities,
        ));
    }

    public function noteAction()
    {
        $em = $this->getDoctrine()->getManager();


        $notes = $em->getRepository('UserBundle:Notification')->findBy(array(), array('dateCreation' => 'desc'));
        $notesC = $em->getRepository('UserBundle:Notification')->findBy(array('traite'=>false), array('dateCreation' => 'desc'));


        return $this->render('AdminBundle:Default:notes.html.twig', array(
            'notes' => $notes,
            'countNotes' => count($notesC)
        ));
    }
    public function redirectAction($id, $relatedTo, Notification $id_note){
        $em = $this->getDoctrine()->getManager();
        $id_note->setTraite(1);
        $em->flush();

        if( $relatedTo == 'guide' or $relatedTo == 'contribution' ){
            return $this->redirect($this->generateUrl('guide_show', array('id' => $id)));
        }

        if( $relatedTo == 'experience' ){
            return $this->redirect($this->generateUrl('experience_show', array('id' => $id)));
        }

        if( $relatedTo == 'lieu' ){
            return $this->redirect($this->generateUrl('lieu_show', array('id' => $id)));
        }


    }
}
