<?php

namespace BestTrip\GuideBundle\Controller;

use BestTrip\GuideBundle\Entity\RecLieu;
use BestTrip\UserBundle\Entity\Commentaire;
use BestTrip\UserBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BestTrip\GuideBundle\Entity\Lieu;
use BestTrip\GuideBundle\Form\LieuType;

use BestTrip\SiteBundle\Entity\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Gd\Imagine;
/**
 * Lieu controller.
 *
 */
class LieuController extends Controller
{

    /**
     * Lists all Lieu entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GuideBundle:Lieu')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $request->query->get('page', 1) /*page number*/,
            9/*limit per page*/
        );

        return $this->render('GuideBundle:Lieu:index.html.twig', array(
            'entities' => $pagination,
        ));
    }
    /**
     * Creates a new Lieu entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Lieu();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image_id = $this->uploadImage($request, '-' . $entity->getNom());
            if ($image_id == -1)
                return $this->render('GuideBundle:Lieu:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),

                ));
            $typeL = $request->get('typeL');


            switch ($typeL) {
                case 'H':
                   $entity->setIsResto(false);
                   $entity->setIsMonument(false);
                   $entity->setIsHotel(true);
                   $entity->setNombreEtoiles($request->get('hotel'));
                    break;
                case 'M':
                    $entity->setIsResto(false);
                    $entity->setIsMonument(true);
                    $entity->setIsHotel(false);
                    $entity->setHistoire($request->get('monument'));
                    break;
                case 'R':
                    $entity->setIsHotel(false);
                    $entity->setIsMonument(false);
                    $entity->setIsResto(true);
                    $entity->setNombreEtoiles($request->get('resto'));
                    break;

            }
            $entity->setUser($this->getUser());
            $entity->setImage($em->getRepository('SiteBundle:Image')->find($image_id));
            $em->persist($entity);
            $em->flush();
            $image = $em->getRepository('SiteBundle:Image')->find($image_id);
            $image->setEntite('lieu');
            $image->setIdEntite($entity->getId());
            $entity->setImage($em->getRepository('SiteBundle:Image')->find($image_id));

            $notif = new Notification();
            $notif->setUser($this->getUser());
            $notif->setDateCreation((new \DateTime('now'))->format('d-m-Y H:i'));
            $notif->setDescription('[Nouveau lieu] ' . $entity->getNom());
            $notif->setEntite('lieu');
            $notif->setIdEntite($entity->getId());
            $notif->setTraite(false);
            $em->persist($notif);
            $em->flush();

            return $this->redirect($this->generateUrl('lieu_show', array('id' => $entity->getId())));
        }

        return $this->render('GuideBundle:Lieu:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Lieu entity.
     *
     * @param Lieu $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Lieu $entity)
    {
        $form = $this->createForm(new LieuType(), $entity, array(
            'action' => $this->generateUrl('lieu_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Lieu entity.
     *
     */
    public function newAction()
    {
        $entity = new Lieu();
        $form   = $this->createCreateForm($entity);

        return $this->render('GuideBundle:Lieu:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Lieu entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuideBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GuideBundle:Lieu:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Lieu entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuideBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GuideBundle:Lieu:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Lieu entity.
    *
    * @param Lieu $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Lieu $entity)
    {
        $form = $this->createForm(new LieuType(), $entity, array(
            'action' => $this->generateUrl('lieu_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Lieu entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuideBundle:Lieu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lieu entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('lieu_edit', array('id' => $id)));
        }

        return $this->render('GuideBundle:Lieu:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Lieu entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GuideBundle:Lieu')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Lieu entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('lieu'));
    }

    /**
     * Creates a form to delete a Lieu entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lieu_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function voteAction(Lieu $lieu, Request $request)
    {
        $rating = $request->get('rating');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$lieu->getRatings()->contains($user)) {
            if ($rating < 0 or $rating > 5) {
                $request->getSession()->getFlashBag()->add('warning', 'La note doit d\'être comprise entre 0 et 5!');
                return $this->redirect($this->generateUrl('lieu_show', array('id' => $lieu->getId())));
            }
            $note = ($lieu->getRating() * $lieu->getNbrRating() + $rating) / ($lieu->getNbrRating() + 1);
            $lieu->setNbrRating($lieu->getNbrRating() + 1);
            $lieu->setRating($note);
            $lieu->addRating($user);
            $em->flush();
            return $this->redirect($this->generateUrl('lieu_show', array('id' => $lieu->getId())));
        }

    }
    public function commentAction(Lieu $lieu, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $c = new Commentaire();
        $c->setUser($user);
        $c->setContenu($request->get('comment'));
        $c->setDateCreation((new \DateTime('now')));
        $c->setBad($request->get('bad'));
        $c->setGood($request->get('good'));
        $em->persist($c);
        $lieu->addCommentaire($c);
        $em->flush();
        return $this->redirect($this->generateUrl('lieu_show', array('id' => $lieu->getId())));
    }

    public function recommendAction(Lieu $lieu, Request $request)
    {
        $state = $request->get('state');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $recLieu = $em->getRepository('GuideBundle:RecLieu')->findOneBy(array('user' => $user, 'lieu' => $lieu));

        if (!$recLieu) {
            $rg = new RecLieu();
            $rg->setLieu($lieu);
            $rg->setUser($user);
            $rg->setState($state);

            if ($state == 1)
                $lieu->setNbrRec($lieu->getNbrRec() + 1);
            else
                $lieu->setNbrNonRec($lieu->getNbrNonRec() + 1);

            $em->persist($rg);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Votre recommendation a été enregistrée!');

            return $this->redirect($this->generateUrl('guide_show', array('id' => $lieu->getId())));
        }
        if ($recLieu->getState() != $state) {
            $recLieu->setState($state);

            if ($state == -1) {
                $lieu->setNbrNonRec($lieu->getNbrNonRec() + 1);
                $lieu->setNbrRec($lieu->getNbrRec() - 1);
            } else {
                $lieu->setNbrNonRec($lieu->getNbrNonRec() - 1);
                $lieu->setNbrRec($lieu->getNbrRec() + 1);
            }

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Votre recommendation a été enregistrée!');

        } else
            $request->getSession()->getFlashBag()->add('warning', 'Vous ne pouvez pas recommender (non recommender) deux fois!');

        return $this->redirect($this->generateUrl('lieu_show', array('id' => $lieu->getId())));

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
                    $path = 'images/lieu';

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




}
