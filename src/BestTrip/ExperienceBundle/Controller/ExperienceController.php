<?php

namespace BestTrip\ExperienceBundle\Controller;

use BestTrip\SiteBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BestTrip\ExperienceBundle\Entity\Experience;
use BestTrip\ExperienceBundle\Entity\Voyage;
use BestTrip\ExperienceBundle\Entity\Compagnie;
use BestTrip\GuideBundle\Entity\Ville;
use BestTrip\UserBundle\Entity\Commentaire;
use BestTrip\UserBundle\Entity\Notification;
use BestTrip\ExperienceBundle\Form\ExperienceType;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Gd\Imagine;

/**
 * Experience controller.
 *
 */
class ExperienceController extends Controller {

    /**
     * Lists all Experience entities.
     *
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ExperienceBundle:Experience')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $entities, $request->query->get('page', 1) /* page number */, 6/* limit per page */
        );

        return $this->render('ExperienceBundle:Experience:index.html.twig', array(
                    'entities' => $pagination,
        ));
    }

    /**
     * Creates a new Experience entity.
     *
     */
    public function createAction(Request $request) {

        $entity = new Experience();
        $experience = new Experience();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $image_id = $this->uploadImage($request, '-' . $entity->getTitre());
            if ($image_id == -1)
                return $this->render('ExperienceBundle:Experience:new.html.twig', array(
                            'entity' => $entity,
                            'form' => $form->createView(),
                            'compagnieDeVoyage' => $compagnieDeVoyage,
                            'ville' => $ville
                ));

            $entity->setDateAjout((new \DateTime('now'))->format("d-m-Y H:i"));
            $entity->setDateDerniereModification((new \DateTime('now'))->format("d-m-Y H:i"));
            $entity->setUser($this->getUser());
            $entity->setImage($em->getRepository('SiteBundle:Image')->find($image_id));
            $entity->setValid(false);
            $em->persist($entity);
$em->flush();
            $experience = $entity;
            //-------------------------------------------------------------------
            for ($i = 0; $i < $request->get('nbre-instances-voyage'); $i++) {
                $entityV[$i] = new Voyage();
                $dateDep = $request->get('dateDepart' . $i);
                $dateArr = $request->get('dateArrivee' . $i);
                $villeDep = $em->getRepository('GuideBundle:Ville')->find($request->get('villeDep' . $i));
                $villeArr = $em->getRepository('GuideBundle:Ville')->find($request->get('villeArr' . $i));
                switch ($request->get('saison' . $i)) {
                    case "0" : $saison = "automne";
                        break;
                    case "1" : $saison = "hiver";
                        break;
                    case "2" : $saison = "printemps";
                        break;
                    case "3" : $saison = "ete";
                        break;
                    default: $saison = "no data available";
                }
                switch ($request->get('transport' . $i)) {
                    case "0" : $transport = "voiture_personnelle";
                        break;
                    case "1" : $transport = "train";
                        break;
                    case "2" : $transport = "avion";
                        break;
                    case "3" : $transport = "bateau";
                        break;
                    default: $transport = "no data available";
                }
                $compagnie = $em->getRepository('ExperienceBundle:Compagnie')->find($request->get('compagnie' . $i));
                $entityV[$i]->setExperience($experience);
                $entityV[$i]->setDateDepart($dateDep);
                $entityV[$i]->setDateArrivee($dateArr);
                $entityV[$i]->setVilleDepart($villeDep);
                $entityV[$i]->setVilleArrivee($villeArr);
                $entityV[$i]->setSaison($saison);
                $entityV[$i]->setMoyenDeTransport($transport);
                $entityV[$i]->setCompagnieDeVoyage($compagnie);
                $em->persist($entityV[$i]);
            }

            $notif = new Notification();
            $notif->setUser($this->getUser());
            $notif->setDateCreation((new \DateTime('now'))->format('d-m-Y H:i'));
            $notif->setDescription('[Nouvelle experience] ' . $entity->getTitre());
            $notif->setEntite('experience');
            $notif->setIdEntite($entity->getId());
            $notif->setTraite(false);
            $em->persist($notif);

            $em->flush();

            //-------------------------------------------------------------------

            return $this->redirect($this->generateUrl('experience_show', array('id' => $entity->getId())));
        }

        return $this->render('ExperienceBundle:Experience:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'compagnieDeVoyage' => $compagnieDeVoyage,
                    'ville' => $ville
        ));
    }

    /**
     * Creates a form to create a Experience entity.
     *
     * @param Experience $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Experience $entity) {
        $form = $this->createForm(new ExperienceType(), $entity, array(
            'action' => $this->generateUrl('experience_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Experience entity.
     *
     */
    public function newAction() {
        $entity = new Experience();
        $form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();
        $compagnieDeVoyage = $em->getRepository('ExperienceBundle:Compagnie')->findAll();
        $ville = $em->getRepository('GuideBundle:Ville')->findAll();

        return $this->render('ExperienceBundle:Experience:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'compagnieDeVoyage' => $compagnieDeVoyage,
                    'ville' => $ville
        ));
    }

    /**
     * Finds and displays a Experience entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $voyages_of_entity = array();
        $entity = $em->getRepository('ExperienceBundle:Experience')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Experience entity.');
        }

        $voyages = $em->getRepository('ExperienceBundle:Voyage')->findByExperience($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ExperienceBundle:Experience:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
                    'voyages' => $voyages
        ));
    }

    /**
     * Displays a form to edit an existing Experience entity.
     *
     */
    public function editAction($id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ExperienceBundle:Experience')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Experience entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        $compagnies = $em->getRepository('ExperienceBundle:Compagnie')->findAll();
        $villes = $em->getRepository('GuideBundle:Ville')->findAll();
        $voyages = $em->getRepository('ExperienceBundle:Voyage')->findByExperience($entity);

        return $this->render('ExperienceBundle:Experience:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'compagnies' => $compagnies,
                    'villes' => $villes,
                    'voyages' => $voyages,
                    'nbVoyages' => count($voyages)
        ));
    }

    /**
     * Creates a form to edit a Experience entity.
     *
     * @param Experience $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Experience $entity) {
        $form = $this->createForm(new ExperienceType(), $entity, array(
            'action' => $this->generateUrl('experience_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Experience entity.
     *
     */
    public function updateAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ExperienceBundle:Experience')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Experience entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($request->get('imageName') instanceof UploadedFile) {
                $image_id = $this->uploadImage($request, '-' . $entity->getTitre());
                if ($image_id == -1)
                    return $this->render('ExperienceBundle:Experience:edit.html.twig', array(
                                'entity' => $entity,
                                'edit_form' => $editForm->createView(),
                                'delete_form' => $deleteForm->createView(),
                    ));
                $image = $em->getRepository('SiteBundle:Image')->find($image_id);

                $image->setEntite('experience');
                $image->setIdEntite($entity->getId());
                $entity->setImage($em->getRepository('SiteBundle:Image')->find($image_id));
            }

            for ($i = 0; $i < $request->get('nbre-instances-voyage'); $i++) {
                $entityV[$i] = new Voyage();
                $dateDep = $request->get('dateDepart' . $i);
                $dateArr = $request->get('dateArrivee' . $i);
                $villeDep = $em->getRepository('GuideBundle:Ville')->find($request->get('villeDep' . $i));
                $villeArr = $em->getRepository('GuideBundle:Ville')->find($request->get('villeArr' . $i));
                switch ($request->get('saison' . $i)) {
                    case "0" : $saison = "automne";
                        break;
                    case "1" : $saison = "hiver";
                        break;
                    case "2" : $saison = "printemps";
                        break;
                    case "3" : $saison = "ete";
                        break;
                    default: $saison = "no data available";
                }
                switch ($request->get('transport' . $i)) {
                    case "0" : $transport = "voiture_personnelle";
                        break;
                    case "1" : $transport = "train";
                        break;
                    case "2" : $transport = "avion";
                        break;
                    case "3" : $transport = "bateau";
                        break;
                    default: $transport = "no data available";
                }
                $compagnie = $em->getRepository('ExperienceBundle:Compagnie')->find($request->get('compagnie' . $i));
                $entityV[$i]->setExperience($entity);
                $entityV[$i]->setDateDepart($dateDep);
                $entityV[$i]->setDateArrivee($dateArr);
                $entityV[$i]->setVilleDepart($villeDep);
                $entityV[$i]->setVilleArrivee($villeArr);
                $entityV[$i]->setSaison($saison);
                $entityV[$i]->setMoyenDeTransport($transport);
                $entityV[$i]->setCompagnieDeVoyage($compagnie);
                $em->persist($entityV[$i]);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('experience_show', array('id' => $id)));
        }

        $compagnies = $em->getRepository('ExperienceBundle:Compagnie')->findAll();
        $villes = $em->getRepository('GuideBundle:Ville')->findAll();
        $voyages = $em->getRepository('ExperienceBundle:Voyage')->findByExperience($entity);
        return $this->render('ExperienceBundle:Experience:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'compagnies' => $compagnies,
                    'villes' => $villes,
                    'voyages' => $voyages,
                    'nbVoyages' => count($voyages)
        ));
    }

    /**
     * Deletes a Experience entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ExperienceBundle:Experience')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Experience entity.');
            }

            $voyages = $em->getRepository('ExperienceBundle:Voyage')->findByExperience($entity);
            foreach ($voyages as $value) {
                $em->remove($value);
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('experience'));
    }

    /**
     * Creates a form to delete a Experience entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('experience_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    public function uploadImage(Request $request, $fileName) {

        $image = $request->files->get('imageName');

        if (($image instanceof UploadedFile) && ($image->getError() == '0')) {
            if (($image->getSize() < 2000000)) {
                $orinalName = $image->getClientOriginalName();
                $name_array = explode('.', $orinalName);
                $file_type = $name_array[sizeof($name_array) - 1];
                $valid_imagetype = array('jpg', 'jpeg', 'png');

                if (in_array(strtolower($file_type), $valid_imagetype)) {
                    $path = 'images/experience';

                    $document = new Image($path);
                    $document->file = $image;
                    $document->setName($fileName);
                    $document->setPath($path . '/' . $document->getName() . '.' . $file_type);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($document);
                    $em->flush();
                    $dc = $em->getRepository('SiteBundle:Image')->find($document->getId());
                    $dc->setName($document->getId() . $fileName);
                    $document->setName($document->getId() . $fileName);
                    $document->setPath($path . '/');
                    $document->upload($file_type);
                    $dc->setPath($path . '/' . $dc->getName() . '.' . $file_type);
                    $em->flush();

                    $imagine = new Imagine();

                    $image = $imagine->open($dc->getPath());
                    $image->resize(new Box(1173, 500))
                            ->save($dc->getPath());
                    return $document->getId();
                } else {
                    $request->getSession()->getFlashBag()->add('error_image', 'Type d\'image invalid! Les types valides sont: jpg, jpeg, png.');
                }
            } else {
                $request->getSession()->getFlashBag()->add('error_image', 'Taille de l\'image doit être inférieur à 2Mo.');
            }
        } else {
            $request->getSession()->getFlashBag()->add('error_image', 'Erreur!');
        }

        return -1;
    }

    public function voteAction(Experience $experience, Request $request) {
        $rating = $request->get('rating');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$experience->getRatings()->contains($user)) {
            if ($rating < 0 or $rating > 5) {
                $request->getSession()->getFlashBag()->add('warning', 'La note doit d\'être comprise entre 0 et 5!');
                return $this->redirect($this->generateUrl('experience_show', array('id' => $experience->getId())));
            }
            $note = ($experience->getRating() * $experience->getNbrRating() + $rating) / ($experience->getNbrRating() + 1);
            $experience->setNbrRating($experience->getNbrRating() + 1);
            $experience->setRating($note);
            $experience->addRating($user);
            $em->flush();
            return $this->redirect($this->generateUrl('experience_show', array('id' => $experience->getId())));
        }
    }

    public function commentAction(Experience $experience, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $c = new Commentaire();
        $c->setUser($user);
        $c->setContenu($request->get('comment'));
        $c->setDateCreation((new \DateTime('now')));
        $c->setBad($request->get('bad'));
        $c->setGood($request->get('good'));
        $em->persist($c);
        $experience->addCommentaire($c);
        $em->flush();
        return $this->redirect($this->generateUrl('experience_show', array('id' => $experience->getId())));
    }

    public function signalerAction(Experience $experience, Request $request) {
       
            $objet = $request->get('objet');
            $corps = $request->get('corps');
            $user = $this->getUser();
            $details = "\nReclamation envoyée par : ".$user->getPrenom()." ".$user->getNom()." - ".$user->getEmail()
                    ."\nExperience signalée : ".$experience->getTitre()." - #".$experience->getId();
            $corps = $corps.$details;
            $mailer = $this->container->get('mailer');
            $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
            $mailer = \Swift_Mailer::newInstance($transport);
            $corps = \Swift_Message::newInstance('test')
                    ->setSubject($objet)
                    ->setFrom('besttripthecrew@gmail.com')
                    ->setTo('reclamationbesttrip@gmail.com')
                    ->setBody($corps);
                    $this->get('mailer')->send($corps);
            return $this->redirect($this->generateUrl('experience_show',array('id'=> $experience->getId())));
        
    }

}
