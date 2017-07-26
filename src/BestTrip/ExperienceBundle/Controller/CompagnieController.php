<?php

namespace BestTrip\ExperienceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BestTrip\ExperienceBundle\Entity\RecCompagnie;
use BestTrip\ExperienceBundle\Entity\Compagnie;
use BestTrip\ExperienceBundle\Form\CompagnieType;
use BestTrip\UserBundle\Entity\Commentaire;

/**
 * Compagnie controller.
 *
 */
class CompagnieController extends Controller
{

    /**
     * Lists all Compagnie entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ExperienceBundle:Compagnie')->findAll();

        return $this->render('ExperienceBundle:Compagnie:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Compagnie entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Compagnie();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('compagnie_show', array('id' => $entity->getId())));
        }

        return $this->render('ExperienceBundle:Compagnie:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Compagnie entity.
     *
     * @param Compagnie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Compagnie $entity)
    {
        $form = $this->createForm(new CompagnieType(), $entity, array(
            'action' => $this->generateUrl('compagnie_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Compagnie entity.
     *
     */
    public function newAction()
    {
        $entity = new Compagnie();
        $form   = $this->createCreateForm($entity);

        return $this->render('ExperienceBundle:Compagnie:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Compagnie entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ExperienceBundle:Compagnie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compagnie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ExperienceBundle:Compagnie:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Compagnie entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ExperienceBundle:Compagnie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compagnie entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ExperienceBundle:Compagnie:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Compagnie entity.
    *
    * @param Compagnie $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Compagnie $entity)
    {
        $form = $this->createForm(new CompagnieType(), $entity, array(
            'action' => $this->generateUrl('compagnie_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Compagnie entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ExperienceBundle:Compagnie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compagnie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('compagnie_edit', array('id' => $id)));
        }

        return $this->render('ExperienceBundle:Compagnie:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Compagnie entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ExperienceBundle:Compagnie')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Compagnie entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('compagnie'));
    }

    /**
     * Creates a form to delete a Compagnie entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('compagnie_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
       public function retrieveData() {
        $experience = $this->findAll();
        $arr1 = [];
        $arr2 = [];
        foreach ($experience as $g) {
            $arr1[] = $g->getTitre();
            $arr2[] = $g->getRating();
        };

        $arr[0] = $arr1;
        $arr[1] = $arr2;
        
        return $arr;
    }
      public function recommendAction(Compagnie $compagnie, Request $request)
    {
        $state = $request->get('state');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $recCompagnie = $em->getRepository('ExperienceBundle:RecCompagnie')->findOneBy(array('user' => $user, 'compagnie' => $compagnie));
        if (!$recCompagnie) {
            $rg = new RecCompagnie();
            $rg->setCompagnie($compagnie);
            $rg->setUser($user);
            $rg->setState($state);
//var_dump($rg);die;

            if ($state == 1)
                $compagnie->setNbrRec($compagnie->getNbrRec() + 1);
            else
                $compagnie->setNbrNonRec($compagnie->getNbrNonRec() + 1);

            $em->persist($rg);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Votre recommendation a été enregistrée!');

            return $this->redirect($this->generateUrl('compagnie_show', array('id' => $compagnie->getId())));
        }
        if ($recCompagnie->getState() != $state) {
            $recCompagnie->setState($state);

            if ($state == -1) {
                $compagnie->setNbrNonRec($compagnie->getNbrNonRec() + 1);
                $compagnie->setNbrRec($compagnie->getNbrRec() - 1);
            } else {
                $compagnie->setNbrNonRec($compagnie->getNbrNonRec() - 1);
                $compagnie->setNbrRec($compagnie->getNbrRec() + 1);
            }

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Votre recommendation a été enregistrée!');

        } else
            $request->getSession()->getFlashBag()->add('warning', 'Vous ne pouvez pas recommender (non recommender) deux fois!');

        return $this->redirect($this->generateUrl('compagnie_show', array('id' => $compagnie->getId())));

    }
    public function voteAction(Compagnie $compagnie, Request $request)
    {
        $rating = $request->get('rating');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$compagnie->getRatings()->contains($user)) {
            if ($rating < 0 or $rating > 5) {
                $request->getSession()->getFlashBag()->add('warning', 'La note doit d\'être comprise entre 0 et 5!');
                return $this->redirect($this->generateUrl('compagnie_show', array('id' => $compagnie->getId())));
            }
            $note = ($compagnie->getRating() * $compagnie->getNbrRating() + $rating) / ($compagnie->getNbrRating() + 1);
            $compagnie->setNbrRating($compagnie->getNbrRating() + 1);
            $compagnie->setRating($note);
            $compagnie->addRating($user);
            $em->flush();
            return $this->redirect($this->generateUrl('compagnie_show', array('id' => $compagnie->getId())));
        }


}
 public function commentAction(Compagnie $compagnie, Request $request)
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
        $compagnie->addCommentaire($c);
        $em->flush();
        return $this->redirect($this->generateUrl('compagnie_show', array('id' => $compagnie->getId())));
    }
}
