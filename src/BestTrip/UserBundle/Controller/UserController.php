<?php

namespace BestTrip\UserBundle\Controller;

use BestTrip\SiteBundle\Entity\Image;
use Imagine\Gd\Imagine;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BestTrip\UserBundle\Entity\User;
use BestTrip\UserBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('UserBundle:User:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        {
            $entity = new User();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {

                $userManager = $this->container->get('fos_user.user_manager');
                $user = $userManager->createUser();

                $user->setNom($entity->getNom());
                $user->setPrenom($entity->getPrenom());
                $user->setDateNaissance(new \DateTime($entity->getDateNaissance()->format('d-m-Y'), new \DateTimeZone('Africa/Tunis')));
                $user->setSexe($entity->getSexe());
                $user->setBio($entity->getBio());

                $user->setUsername($entity->getUsername());
                $user->setPassword($entity->getPlainPassword());
                $user->setEmail($entity->getEmail());
                $user->setRoles('ROLE_USER');
                $user->setEnabled(1);

                $userManager->updateUser($user);

                return $this->redirect($this->generateUrl('user_show', array('id' => $user->getId())));
            }
            return $this->render('UserBundle:User:new.html.twig', array(
                'entity' => $entity,
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);

        return $this->render('UserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('UserBundle:User:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('UserBundle:User:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('UserBundle:User')->find($id);

        if ($user != $this->getUser() ||  !$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($user);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $image_id = $this->uploadImage($request, $user->getId()."-profile");


            $em = $this->getDoctrine()->getManager();
            $image = $em->getRepository('SiteBundle:Image')->find($image_id);
            $image->setEntite('profile');
            $image->setIdEntite($user->getId());
            $user->setImage($em->getRepository('SiteBundle:Image')->find($image_id));

            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('UserBundle:User:edit.html.twig', array(
            'entity' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {



            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }


    public function activateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('UserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        if ($request->get('action') == 1) {
            $user->setEnabled(1);
        } else {
            $user->setEnabled(0);
        }

        $em->flush();

        return $this->redirect($this->generateUrl('user'));
    }

    function rechercheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findAll();
        $form = $this->createForm(new UserType());
        $Request = $this->getRequest();
        if ($Request->getMethod() == 'POST') {
            $search = $Request->get('search');
            $users = $em->getRepository('UserBundle:User')->findBy(array("username" => $search));
        }
        return $this->render('UserBundle:User:recherche.html.twig', array("users" => $users));
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
                    $path = 'images/profile';

                    $document = new Image($path);
                    $document->file = $image;
                    $document->setName($fileName);
                    $document->setPath($path . '/' . $document->getName() . '.' . $file_type);
                    $em = $this->getDoctrine()->getManager();
                    $document->setName($document->getId() . $fileName);
                    $document->setPath($path . '/');
                    $document->upload($file_type);
                    $document->setPath($path . '/' . $document->getName() . '.' . $file_type);
                    $em->persist($document);
                    $em->flush();
                    $imagine = new Imagine();

                    $image = $imagine->open($document->getPath());
                    $image->resize(new Box(500, 500))
                        ->save($document->getPath());
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

    public function fluxRssAction() {
        $i = 0;
        $tab = [];
        $xml = simplexml_load_file("http://www.bulletins-electroniques.com/rss/actualites/tags/transports.xml") or die("Error: Cannot create object");
        // $xml = simplexml_load_file("http://www.leparisien.fr/voyages/rss.xml") or die("Error: Cannot create object");
        // $xml = simplexml_load_file("http://www.bfmtv.com/rss/planete/environnement/innovation/") or die("Error: Cannot create object");


        foreach ($xml->channel[0]->item as $x) {

            if (strlen($x->title)>80)
            {$tab[$i]['titre'] = substr($x->title, 0, 80)."...";}
            else
            {$tab[$i]['titre'] = $x->title;}

            //$tab[$i]['titre'] = substr($x->title,0,  3);
            $tab[$i]['description'] =$x->description;
            $tab[$i]['date'] = $x->pubDate;
            $tab[$i]['lien'] =$x->link;
            $i++;
        }
        return $this->render('UserBundle:User:rss.html.twig', array('list' => $tab));
    }

    public function voirPlusAction(Request $request) {
        //$pays = $request->get('pays');

        $em = $this->getDoctrine()->getManager();
        $contenu = $request->get('contenu');

        $response = new Response();

        $str = "<h4>" . $contenu . "</h4>";

        $response->setContent($str);

        return $response;
    }

    public function testAutoCompleteAction(Request $request) {

        $response = new Response();
        $contenu = $request->get('contenu');
        $em = $this->getDoctrine()->getManager();
        $list = $em->getRepository('UserBundle:User')->findByName($contenu);

        for ($k = 0, $size = count($list); $k < $size; ++$k) {
            if ($list[$k]->getId() == $this->getUser()->getId())
                unset($list[$k]);
        }
        $list = array_values($list);


        $str = "";

//        foreach ($list as $l) {
//
//            $str.=$l->getPrenom() ." ".$l->getNom(). '<br>';
//        }


        //$str = "<option value='-1'>Choisissez un utilisateur!</option>";
        foreach ($list as $u) {
            $str .= "<option value='" . $u->getId() . "'>" . $u->getPrenom() ." ".$u->getNom(). "</option>";
        }




        $response->setContent($str);

        return $response;
    }

    public function showTestAction() {
        return $this->render('UserBundle:User:test.html.twig');
    }

}
