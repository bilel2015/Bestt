<?php

namespace BestTrip\GuideBundle\Controller;

use BestTrip\GuideBundle\Entity\Contribution;
use BestTrip\GuideBundle\Entity\RecGuide;
use BestTrip\SiteBundle\Entity\Image;
use BestTrip\UserBundle\Entity\Commentaire;
use BestTrip\UserBundle\Entity\Notification;
use Imagine\Gd\Imagine;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BestTrip\GuideBundle\Entity\Guide;
use BestTrip\GuideBundle\Form\GuideType;
use Symfony\Component\HttpFoundation\Response;

use Imagine\Image\Box;
use Imagine\Image\Point;

/**
 * Guide controller.
 *
 */
class GuideController extends Controller
{

    /**
     * Lists all Guide entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if($request->get('sort') != '' ){
            $entities = $em->getRepository('GuideBundle:Guide')->findBy(array(), array($request->get('sort')=>$request->get('order')));
        }else{
            $critere = array(
                'description' => '',
                'titre' => '',
                'rating' => '',
                'pays' => '',
            );

            if($request->get('description') != ''){
                $critere['description'] = $request->get('description');
            }
            if($request->get('stars') != ''){
                $critere['rating'] = $request->get('stars');
            }
            if($request->get('pays') != ''){
                $critere['pays'] = $request->get('pays');
            }
            if($request->get('titre') != ''){
                $critere['titre'] = $request->get('titre');
            }
            $entities = $em->getRepository('GuideBundle:Guide')->findByCritere($critere);
        }


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $request->query->get('page', 1) /*page number*/,
            9/*limit per page*/
        );

        return $this->render('GuideBundle:Guide:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Lists all Guide entities.
     *
     */
    public function ownedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if($request->get('sort') != '' ){
            $entities = $em->getRepository('GuideBundle:Guide')->findBy(array('user' => $this->getUser()),array($request->get('sort')=>$request->get('order')));
        }else{
            $critere = array(
                'description' => '',
                'titre' => '',
                'rating' => '',
                'pays' => '',
            );

            if($request->get('description') != ''){
                $critere['description'] = $request->get('description');
            }
            if($request->get('stars') != ''){
                $critere['rating'] = $request->get('stars');
            }
            if($request->get('pays') != ''){
                $critere['pays'] = $request->get('pays');
            }
            if($request->get('titre') != ''){
                $critere['titre'] = $request->get('titre');
            }
            $entities = $em->getRepository('GuideBundle:Guide')->findByCritereAndUser($critere, $this->getUser());
        }


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $request->query->get('page', 1) /*page number*/,
            9/*limit per page*/
        );

        return $this->render('GuideBundle:Guide:owned.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Lists all Guide entities.
     *
     */
    public function contributionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if($request->get('sort') != '' ){
            $entities = $em->getRepository('GuideBundle:Guide')->findBy(array('user' => $this->getUser()),array($request->get('sort')=>$request->get('order')));
        }else{
            $critere = array(
                'description' => '',
                'titre' => '',
                'rating' => '',
                'pays' => '',
            );

            if($request->get('description') != ''){
                $critere['description'] = $request->get('description');
            }
            if($request->get('stars') != ''){
                $critere['rating'] = $request->get('stars');
            }
            if($request->get('pays') != ''){
                $critere['pays'] = $request->get('pays');
            }
            if($request->get('titre') != ''){
                $critere['titre'] = $request->get('titre');
            }
            $entities = $em->getRepository('GuideBundle:Guide')->findByCritereAndUser($critere, $this->getUser());
        }


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $request->query->get('page', 1) /*page number*/,
            9/*limit per page*/
        );

        return $this->render('GuideBundle:Guide:contributions.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Creates a new Guide entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Guide();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $image_id = $this->uploadImage($request, '-' . $entity->getTitre());
            if ($image_id == -1)
                return $this->render('GuideBundle:Guide:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));

            for ($i = 0; $i < $request->get('nbre-instances-ville'); $i++) {
                if ($request->get('ville-' . $i) != -1) {
                    $villes[$i] = $em->getRepository('GuideBundle:Ville')->find($request->get('ville-' . $i));
                    if (!$entity->getVilles()->contains($villes[$i]))
                        $entity->addVilles($villes[$i]);
                }
            }

            for ($i = 0; $i < $request->get('nbre-instances-lieu'); $i++) {
                if ($request->get('content-lieu-' . $i) != -1) {
                    $villes[$i] = $em->getRepository('GuideBundle:Lieu')->find($request->get('content-lieu-' . $i));
                    if (!$entity->getLieux()->contains($villes[$i]))
                        $entity->addLieux($villes[$i]);
                }
            }

            $autres = [];
            for ($i = 0; $i < $request->get('nbre-instances-autre'); $i++) {
                if ($request->get('autre-' . $i) == -1) {
                    $autres[$request->get('libele-autre-' . $i)] = $request->get('contenu-autre-' . $i);
                }
            }

            if (count($autres) != 0) {
                $entity->setAutres(json_encode($autres));
            }
            $entity->setDateCreation((new \DateTime('now'))->format("d-m-Y H:i"));
            $entity->setUser($this->getUser());
            $entity->setImage($em->getRepository('SiteBundle:Image')->find($image_id));
            $em->persist($entity);
            $em->flush();
            $image = $em->getRepository('SiteBundle:Image')->find($image_id);
            $image->setEntite('guide');
            $image->setIdEntite($entity->getId());
            $entity->setImage($em->getRepository('SiteBundle:Image')->find($image_id));

            $notif = new Notification();
            $notif->setUser($this->getUser());
            $notif->setDateCreation((new \DateTime('now'))->format('d-m-Y H:i'));
            $notif->setDescription('[Nouveau guide] '.$entity->getTitre());
            $notif->setEntite('guide');
            $notif->setIdEntite($entity->getId());
            $notif->setTraite(false);
            $em->persist($notif);

            $em->flush();
            return $this->redirect($this->generateUrl('guide_show', array('id' => $entity->getId())));
        }

        return $this->render('GuideBundle:Guide:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Guide entity.
     *
     * @param Guide $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Guide $entity)
    {
        $form = $this->createForm(new GuideType(), $entity, array(
            'action' => $this->generateUrl('guide_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Guide entity.
     *
     */
    public function newAction()
    {
        $entity = new Guide();
        $form = $this->createCreateForm($entity);

        return $this->render('GuideBundle:Guide:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Guide entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuideBundle:Guide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guide entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('GuideBundle:Guide:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Guide entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuideBundle:Guide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guide entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $villes = $em->getRepository('GuideBundle:Ville')->findByPays($entity->getPays());

        $restos = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isResto' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));

        $monuments = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isMonument' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));

        $hotels = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isHotel' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));


        return $this->render('GuideBundle:Guide:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'villes' => $villes,
            'restos' => $restos,
            'hotels' => $hotels,
            'monuments' => $monuments,
        ));
    }

    /**
     * Creates a form to edit a Guide entity.
     *
     * @param Guide $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Guide $entity)
    {
        $form = $this->createForm(new GuideType(), $entity, array(
            'action' => $this->generateUrl('guide_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Guide entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuideBundle:Guide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guide entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($request->get('imageName') instanceof UploadedFile) {
                $image_id = $this->uploadImage($request, '-' . $entity->getTitre());
                if ($image_id == -1)
                    return $this->render('GuideBundle:Guide:edit.html.twig', array(
                        'entity' => $entity,
                        'edit_form' => $editForm->createView(),
                        'delete_form' => $deleteForm->createView(),
                    ));
                $image = $em->getRepository('SiteBundle:Image')->find($image_id);

                $image->setEntite('guide');
                $image->setIdEntite($entity->getId());
                $entity->setImage($em->getRepository('SiteBundle:Image')->find($image_id));
            }

            foreach ($entity->getVilles() as $ville) {
                if ($request->get('-o-ville-' . $ville->getId()) == -1) {
                    if ($request->get('-o-ville-id-' . $ville->getId()) != $ville->getId()) {
                        if (!$entity->getVilles()->contains($em->getRepository('GuideBundle:Ville')->find($request->get('-o-ville-id-' . $ville->getId())))) {
                            $entity->addVilles($em->getRepository('GuideBundle:Ville')->find($request->get('-o-ville-id-' . $ville->getId())));
                        }
                    }
                } else {
                    $entity->removeVille($ville);
                }
            }

            foreach ($entity->getLieux() as $lieu) {
                if ($request->get('-o-lieu-' . $lieu->getId()) == -1) {
                    if ($request->get('-o-content-lieu-' . $lieu->getId()) != $lieu->getId()) {
                        if (!$entity->getLieux()->contains($em->getRepository('GuideBundle:Lieu')->find($request->get('-o-content-lieu-' . $lieu->getId())))) {
                            $entity->addLieux($em->getRepository('GuideBundle:Lieu')->find($request->get('-o-content-lieu-' . $lieu->getId())));
                        }
                    }
                } else {
                    $entity->removeLieu($lieu);
                }
            }
            $new_autres = [];

            for ($i = 1; $i <= $request->get('-o-autre-count'); $i++)
                if ($request->get('-o-autre-' . $i) == -1) {
                    $lib = $request->get('-o-autre-libelle-' . $i);
                    $text = $request->get('-o-autre-contenu-' . $i);
                    $new_autres[$lib] = $text;
                }


            for ($i = 0; $i < $request->get('nbre-instances-ville'); $i++) {
                if ($request->get('ville-' . $i) != -1) {
                    $villes[$i] = $em->getRepository('GuideBundle:Ville')->find($request->get('ville-' . $i));
                    if (!$entity->getVilles()->contains($villes[$i]))
                        $entity->addVilles($villes[$i]);
                }
            }

            for ($i = 0; $i < $request->get('nbre-instances-lieu'); $i++) {
                if ($request->get('content-lieu-' . $i) != -1) {
                    $villes[$i] = $em->getRepository('GuideBundle:Lieu')->find($request->get('content-lieu-' . $i));
                    if (!$entity->getLieux()->contains($villes[$i]))
                        $entity->addLieux($villes[$i]);
                }
            }

            $autres = [];
            for ($i = 0; $i < $request->get('nbre-instances-autre'); $i++) {
                if ($request->get('autre-' . $i) == -1) {
                    $autres[$request->get('libele-autre-' . $i)] = $request->get('contenu-autre-' . $i);
                }
            }

            $new_autres = array_merge($new_autres, $autres);
            $entity->setAutres(json_encode($new_autres));

            $em->flush();

            return $this->redirect($this->generateUrl('guide_show', array('id' => $id)));
        }

        $villes = $em->getRepository('GuideBundle:Ville')->findByPays($entity->getPays());

        $restos = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isResto' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));

        $monuments = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isMonument' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));

        $hotels = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isHotel' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));

        return $this->render('GuideBundle:Guide:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'villes' => $villes,
            'restos' => $restos,
            'hotels' => $hotels,
            'monuments' => $monuments,
        ));
    }


    /**
     * Displays a form to edit an existing Guide entity.
     *
     */
    public function contributeAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GuideBundle:Guide')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Guide entity.');
        }

        if ($this->getUser() == $entity->getUser()) {
            return $this->redirect($this->generateUrl('guide_show', array('id' => $entity->getId())));
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $villes = $em->getRepository('GuideBundle:Ville')->findByPays($entity->getPays());

        $restos = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isResto' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));

        $monuments = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isMonument' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));

        $hotels = $em->getRepository('GuideBundle:Lieu')->findBy(array(
            'isHotel' => true,
            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
        ));


        return $this->render('GuideBundle:Guide:contribute.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'villes' => $villes,
            'restos' => $restos,
            'hotels' => $hotels,
            'monuments' => $monuments,
        ));
    }

    /**
     * Edits an existing Guide entity.
     *
     */
    public function contributeCreateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GuideBundle:Guide')->find($id);

        $contribution = new Contribution();
        $contribution->setDescr($request->get('description'));
        $contribution->setDateContribution((new \DateTime('now')));
        $contribution->setUser($this->getUser());
        for ($i = 0; $i < $request->get('nbre-instances-ville'); $i++) {
            if ($request->get('ville-' . $i) != -1) {
                $villes[$i] = $em->getRepository('GuideBundle:Ville')->find($request->get('ville-' . $i));
                if (!$entity->getVilles()->contains($villes[$i]))
                    $contribution->addVilles($villes[$i]);
            }
        }

        for ($i = 0; $i < $request->get('nbre-instances-lieu'); $i++) {
            if ($request->get('content-lieu-' . $i) != -1) {
                $villes[$i] = $em->getRepository('GuideBundle:Lieu')->find($request->get('content-lieu-' . $i));
                if (!$entity->getLieux()->contains($villes[$i]))
                    $contribution->addLieux($villes[$i]);
            }
        }

        $autres = [];
        for ($i = 0; $i < $request->get('nbre-instances-autre'); $i++) {
            if ($request->get('autre-' . $i) == -1) {
                $autres[$request->get('libele-autre-' . $i)] = $request->get('contenu-autre-' . $i);
            }
        }

        $contribution->setGuide($entity);
        $contribution->setAutreInfo(json_encode($autres));
        $em->persist($contribution);

        $entity->addContribution($contribution);
        $notif = new Notification();
        $notif->setUser($this->getUser());
        $notif->setDateCreation((new \DateTime('now'))->format('d-m-Y H:i'));
        $notif->setDescription('[Contribution guide #'.$entity->getId().'] '.$entity->getTitre());
        $notif->setEntite('contribution');
        $notif->setIdEntite($contribution->getId());
        $notif->setTraite(false);
        $em->persist($notif);

        $em->flush();

        return $this->redirect($this->generateUrl('guide_show', array('id' => $id)));
//        }
//
//        $villes = $em->getRepository('GuideBundle:Ville')->findByPays($entity->getPays());
//
//        $restos = $em->getRepository('GuideBundle:Lieu')->findBy(array(
//            'isResto'=>true,
//            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
//        ));
//
//        $monuments = $em->getRepository('GuideBundle:Lieu')->findBy(array(
//            'isMonument'=>true,
//            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
//        ));
//
//        $hotels = $em->getRepository('GuideBundle:Lieu')->findBy(array(
//            'isHotel'=>true,
//            'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($entity->getPays()))
//        ));
//
//
//
//        return $this->render('GuideBundle:Guide:contribute.html.twig', array(
//            'entity' => $entity,
//            'villes' => $villes,
//            'restos' => $restos,
//            'hotels' => $hotels,
//            'monuments' => $monuments,
//        ));
    }

    /**
     * Deletes a Guide entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GuideBundle:Guide')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Guide entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('guide'));
    }

    /**
     * Creates a form to delete a Guide entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('guide_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm();
    }

    public function uploadImage(Request $request, $fileName)
    {

        $image = $request->files->get('imageName');

        if (($image instanceof UploadedFile) && ($image->getError() == '0')) {
            if (($image->getSize() < 2000000)) {
                $orinalName = $image->getClientOriginalName();
                $name_array = explode('.', $orinalName);
                $file_type = $name_array[sizeof($name_array) - 1];
                $valid_imagetype = array('jpg', 'jpeg', 'png');

                if (in_array(strtolower($file_type), $valid_imagetype)) {
                    $path = 'images/guide';

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

    public function ajaxPaysVilleAction(Request $request)
    {
        $pays = $request->get('pays');

        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($pays));

        $response = new Response();

        $str = "<option value='-1'>Choisissez une ville!</option>";
        foreach ($villes as $ville) {
            $str .= "<option value='" . $ville->getId() . "'>" . $ville->getNom() . "</option>";
        }
        $response->setContent($str);

        return $response;
    }


    public function ajaxPaysLieuAction(Request $request)
    {
        $pays = $request->get('pays');
        $typeL = $request->get('typeL');

        $em = $this->getDoctrine()->getManager();

        $lieux = null;
        switch ($typeL) {
            case 'R':
                $lieux = $em->getRepository('GuideBundle:Lieu')->findBy(array(
                    'isResto' => true,
                    'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($pays))
                ));
                break;
            case 'M':
                $lieux = $em->getRepository('GuideBundle:Lieu')->findBy(array(
                    'isMonument' => true,
                    'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($pays))
                ));
                break;
            case 'H':
                $lieux = $em->getRepository('GuideBundle:Lieu')->findBy(array(
                    'isHotel' => true,
                    'ville' => $em->getRepository('GuideBundle:Ville')->findByPays($em->getRepository('GuideBundle:Pays')->find($pays))
                ));
                break;

        }

        $response = new Response();

        $str = "<option value='-1'>Choisissez une ville!</option>";

        foreach ($lieux as $ville) {
            $str .= "<option value='" . $ville->getId() . "'>" . $ville->getNom() . "</option>";
        }
        $response->setContent($str);

        return $response;
    }

    public function recommendAction(Guide $guide, Request $request)
    {
        $state = $request->get('state');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $recGuide = $em->getRepository('GuideBundle:RecGuide')->findOneBy(array('user' => $user, 'guide' => $guide));

        if (!$recGuide) {
            $rg = new RecGuide();
            $rg->setGuide($guide);
            $rg->setUser($user);
            $rg->setState($state);

            if ($state == 1)
                $guide->setNbrRec($guide->getNbrRec() + 1);
            else
                $guide->setNbrNonRec($guide->getNbrNonRec() + 1);

            $em->persist($rg);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Votre recommendation a été enregistrée!');

            return $this->redirect($this->generateUrl('guide_show', array('id' => $guide->getId())));
        }
        if ($recGuide->getState() != $state) {
            $recGuide->setState($state);

            if ($state == -1) {
                $guide->setNbrNonRec($guide->getNbrNonRec() + 1);
                $guide->setNbrRec($guide->getNbrRec() - 1);
            } else {
                $guide->setNbrNonRec($guide->getNbrNonRec() - 1);
                $guide->setNbrRec($guide->getNbrRec() + 1);
            }

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Votre recommendation a été enregistrée!');

        } else
            $request->getSession()->getFlashBag()->add('warning', 'Vous ne pouvez pas recommender (non recommender) deux fois!');

        return $this->redirect($this->generateUrl('guide_show', array('id' => $guide->getId())));

    }

    public function voteAction(Guide $guide, Request $request)
    {
        $rating = $request->get('rating');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$guide->getRatings()->contains($user)) {
            if ($rating < 0 or $rating > 5) {
                $request->getSession()->getFlashBag()->add('warning', 'La note doit d\'être comprise entre 0 et 5!');
                return $this->redirect($this->generateUrl('guide_show', array('id' => $guide->getId())));
            }
            $note = ($guide->getRating() * $guide->getNbrRating() + $rating) / ($guide->getNbrRating() + 1);
            $guide->setNbrRating($guide->getNbrRating() + 1);
            $guide->setRating($note);
            $guide->addRating($user);
            $em->flush();
            return $this->redirect($this->generateUrl('guide_show', array('id' => $guide->getId())));
        }

    }
    public function commentAction(Guide $guide, Request $request)
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
        $guide->addCommentaire($c);
        $em->flush();
        return $this->redirect($this->generateUrl('guide_show', array('id' => $guide->getId())));
    }
}
