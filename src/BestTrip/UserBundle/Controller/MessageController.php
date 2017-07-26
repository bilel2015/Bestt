<?php

namespace BestTrip\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BestTrip\UserBundle\Entity\Message;
use BestTrip\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{

    public function envoiAction()
    {

        $msg = new Message();
        $Request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();

        $users = $em->getRepository('UserBundle:User')->findAll();
        for ($i = 0, $size = count($users); $i < $size; ++$i) {
            if ($users[$i]->getId() == $this->getUser()->getId())
                unset($users[$i]);
        }
        $users = array_values($users);
        if ($Request->getMethod() == 'POST') {
            $contenu = $Request->get('contenu');
            $destinataire = $em->getRepository('UserBundle:User')->find($Request->get('destinataire'));

            $msg->setContenu($contenu);
            $msg->setEmetteur($this->getUser());
            $msg->setDestinataire($destinataire);
            $msg->setDate(new \DateTime("now"));


            $em->persist($msg);
            $em->flush();
            return $this->redirect($this->generateUrl("conversation_by_user", array('user' => $destinataire->getId())));
        }

        return $this->render('UserBundle:Chat:envoi.html.twig', array('users' => $users));
    }

    public function supprimer_receptionAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $msg = $em->getRepository('UserBundle:Message')->find($id);
        $em->remove($msg);
        $em->flush();
        return $this->redirect($this->generateUrl("boite_reception"));
    }

    public function supprimer_envoiAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $msg = $em->getRepository('UserBundle:Message')->find($id);
        $em->remove($msg);
        $em->flush();
        return $this->redirect($this->generateUrl("boite_envoi"));
    }

    public function affReceptionAction()
    {

        $msgf = [];
        $k = 0;
        $c = 0;
        $em = $this->getDoctrine()->getEntityManager();
        $msg = $em->getRepository('UserBundle:Message')->findByDestinataire($this->getUser());
        $users = $em->getRepository('UserBundle:User')->findAll();
        for ($i = 0, $size = count($msg); $i < $size; ++$i) {
            if ($msg[$i]->getLu() == 0)
                $c++;
        }

        for ($j = count($msg) - 1; $j > -1; --$j) {
            $msgf[$k++] = $msg[$j];
        }

        for ($k = 0, $size = count($users); $k < $size; ++$k) {
            if ($users[$k]->getId() == $this->getUser()->getId())
                unset($users[$k]);
        }
        $users = array_values($users);

        return $this->render('UserBundle:Chat:affReception.html.twig', array('msg' => $msgf, 'nonLus' => $c, 'users' => $users, 'idd' => $this->getUser()));
    }

    public function affEnvoiAction()
    {

        $msgf = [];
        $k = 0;
        $em = $this->getDoctrine()->getEntityManager();
        $msg = $em->getRepository('UserBundle:Message')->findByEmetteur($this->getUser());
        $users = $em->getRepository('UserBundle:User')->findAll();
        for ($j = count($msg) - 1; $j > -1; --$j) {
            $msgf[$k++] = $msg[$j];
        }
        for ($i = 0, $size = count($users); $i < $size; ++$i) {
            if ($users[$i]->getId() == $this->getUser()->getId())
                unset($users[$i]);
        }
        $users = array_values($users);

        return $this->render('UserBundle:Chat:affEnvoi.html.twig', array('msg' => $msgf, 'users' => $users, 'idd' => $this->getUser()));

    }

    public function conversationAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $users = $em->getRepository('UserBundle:User')->findAll();
        $user = $em->getRepository('UserBundle:User')->find($request->get('user'));
        //$msge=$em->getRepository('UserBundle:Message')->findByEmetteur($user)+$em->getRepository('UserBundle:Message')->findByDestinataire($user);
        //$msgr=$em->getRepository('UserBundle:Message')->findByDestinataire($user);
        //$msgr=$em->getRepository('UserBundle:Message')->findBy(array('emetteur' => $user,'destinataire' => $this->getUser()));
        //$em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT m FROM UserBundle:Message m WHERE m.destinataire = :destinataire and m.emetteur = :emetteur or '
            . 'm.destinataire = :destinataire2 and m.emetteur = :emetteur2 ');
        $query->setParameter('destinataire', $this->getUser());
        $query->setParameter('emetteur', $user);
        $query->setParameter('destinataire2', $user);
        $query->setParameter('emetteur2', $this->getUser());

        //$query->setParameters(array('destinataire' => $user ,'emetteur' => $this->getUser()));

        $msgr = $query->getResult();
        for ($i = 0, $size = count($msgr); $i < $size; ++$i) {
            if ($msgr[$i]->getLu() == 0 && $msgr[$i]->getEmetteur()->getId() == $user->getId())
                $msgr[$i]->setLu(1);
        }

        for ($k = 0, $size = count($users); $k < $size; ++$k) {
            if ($users[$k]->getId() == $this->getUser()->getId())
                unset($users[$k]);
        }
        $users = array_values($users);

        return $this->render('UserBundle:Chat:conversation.html.twig', array('msg' => $msgr, 'user' => $user, 'myId' => $this->getUser()->getId(), 'users' => $users, 'idd' => $this->getUser()));
    }

    public function conversationByUserAction(User $user)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $users = $em->getRepository('UserBundle:User')->findAll();

        //$user = $em->getRepository('UserBundle:User')->find($request->get('user'));
        //$msge=$em->getRepository('UserBundle:Message')->findByEmetteur($user)+$em->getRepository('UserBundle:Message')->findByDestinataire($user);
        //$msgr=$em->getRepository('UserBundle:Message')->findByDestinataire($user);
        //$msgr=$em->getRepository('UserBundle:Message')->findBy(array('emetteur' => $user,'destinataire' => $this->getUser()));
        //$em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT m FROM UserBundle:Message m WHERE m.destinataire = :destinataire and m.emetteur = :emetteur or '
            . 'm.destinataire = :destinataire2 and m.emetteur = :emetteur2 ');
        $query->setParameter('destinataire', $this->getUser());
        $query->setParameter('emetteur', $user);
        $query->setParameter('destinataire2', $user);
        $query->setParameter('emetteur2', $this->getUser());

        //$query->setParameters(array('destinataire' => $user ,'emetteur' => $this->getUser()));

        $msgr = $query->getResult();

        for ($i = 0, $size = count($msgr); $i < $size; ++$i) {
            if ($msgr[$i]->getLu() == 0 && $msgr[$i]->getEmetteur()->getId() == $user->getId())
                $msgr[$i]->setLu(1);
        }

        for ($k = 0, $size = count($users); $k < $size; ++$k) {
            if ($users[$k]->getId() == $this->getUser()->getId())
                unset($users[$k]);
        }
        $users = array_values($users);


        $em->flush();

        return $this->render('UserBundle:Chat:conversation.html.twig', array('msg' => $msgr, 'user' => $user, 'myId' => $this->getUser()->getId(), 'users' => $users, 'idd' => $this->getUser()));
    }

    public function msgCreateAction(User $user, Request $request)
    {
        $msg = new Message();


        $contenu = $request->get('contenu');
        $msg->setContenu($contenu);
        $msg->setEmetteur($this->getUser());
        $msg->setDestinataire($user);
        $msg->setDate(new \DateTime("now"));
        $em = $this->getDoctrine()->getEntityManager();

        $em->persist($msg);
        $em->flush();

        $response = new Response();

        $str = '<li class="self">' .
            '<div class="avatar">' .
            '  <img src="/images/profile/prof.png"/>' .
            ' </div>' .
            ' <div class="messages">' .
            '       <p>' . $msg->getContenu() . '</p>' .
            ' <time datetime="2009-11-13T20:00">Moi • '.(new \DateTime("now"))->format('d-m-Y H:i').'</time>' .
            '</div>' .
            ' </li>';
        $response->setContent($str);

        return $response;

    }

    public function choisirAction()
    {
        $Request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();

        $users = $em->getRepository('UserBundle:User')->findAll();

        for ($i = 0, $size = count($users); $i < $size; ++$i) {
            if ($users[$i]->getId() == $this->getUser()->getId())
                unset($users[$i]);
        }
        $users = array_values($users);

//        foreach ($users as $u){
//            if ($u->getId()==$this->getUser()->getId())            
//        }

        return $this->render('UserBundle:Chat:choix.html.twig', array('users' => $users, 'idd' => $this->getUser()));
    }

    public function DernierMsgAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $users = $em->getRepository('UserBundle:User')->findAll();
        $userss = $users;
        $derMsg = array();
        $matrice = [];
        $j = 0;

        for ($i = 0, $size = count($users); $i < $size; ++$i) {

            $query = $em->createQuery(
                'SELECT m FROM UserBundle:Message m WHERE m.destinataire = :destinataire and m.emetteur = :emetteur or '
                . 'm.destinataire = :destinataire2 and m.emetteur = :emetteur2');
            $query->setParameter('destinataire', $this->getUser());
            $query->setParameter('emetteur', $users[$i]);
            $query->setParameter('destinataire2', $users[$i]);
            $query->setParameter('emetteur2', $this->getUser());
            $msgr = $query->getResult();

            if (count($msgr) == 0)
                unset($users[$i]);
            else {
                $derMsg[$j] = $msgr[count($msgr) - 1];
                $j++;
            }
        }
        $users = array_values($users);
        // var_dump($users);die;
        for ($i = 0; $i < count($users); ++$i) {
            $matrice[$i]['user'] = $users[$i];
            $matrice[$i]['message'] = $derMsg[$i];
        }

        
        $c = 0;

        $msg = $em->getRepository('UserBundle:Message')->findByDestinataire($this->getUser());

        for ($i = 0, $size = count($msg); $i < $size; ++$i) {
            if ($msg[$i]->getLu() == 0)
                $c++;
        }

        for ($k = 0, $size = count($userss); $k < $size; ++$k) {
            if ($userss[$k]->getId() == $this->getUser()->getId())
                unset($userss[$k]);
        }
        $userss = array_values($userss);


        return $this->render('UserBundle:Chat:dernier.html.twig', array('matrice' => $matrice, 'myId' => $this->getUser()->getId(), 'nonLus' => $c, 'users' => $userss, 'idd' => $this->getUser()));
    }


    public function nonLusAction()
    {

        $c = 0;
        $em = $this->getDoctrine()->getEntityManager();
        $msg = $em->getRepository('UserBundle:Message')->findByDestinataire($this->getUser());

        for ($i = 0, $size = count($msg); $i < $size; ++$i) {
            if ($msg[$i]->getLu() == 0)
                $c++;
        }

        return $this->render('UserBundle:Chat:rien.html.twig',array('nonLus' => $c));
    }

    public function MarquerAction()
    {


        $em = $this->getDoctrine()->getEntityManager();
        $msg = $em->getRepository('UserBundle:Message')->findByDestinataire($this->getUser());
        $users = $em->getRepository('UserBundle:User')->findAll();

        for ($i = 0, $size = count($msg); $i < $size; ++$i) {
            if ($msg[$i]->getLu() == 0)
                $msg[$i]->setLu(1);
        }

        for ($k = 0, $size = count($users); $k < $size; ++$k) {
            if ($users[$k]->getId() == $this->getUser()->getId())
                unset($users[$k]);
        }
        $users = array_values($users);


        $em->flush();
        $c = 0;
        return $this->render('UserBundle:Chat:affReception.html.twig', array('msg' => $msg, 'nonLus' => $c, 'users' => $users, 'idd' => $this->getUser()));

    }
    
    public function ajaxPopUpAction(Request $request)
    {
        //$pays = $request->get('pays');

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findAll();

        for ($k = 0, $size = count($users); $k < $size; ++$k) {
            if ($users[$k]->getId() == $this->getUser()->getId())
                unset($users[$k]);
        }
        $users = array_values($users);
        
        
        $response = new Response();

        $str = "<option value='-1'></option>";
        foreach ($users as $user) {
            $str .= "<option value='" . $user->getId() . "'>" . $user . "</option>";
        }
        $response->setContent($str);

        return $response;
    }
    
    
    
    public function envoiPopUpAction(Request $request)
    {
        $msg = new Message();
        $user  = $request->get('user');
        $contenu = $request->get('contenu');
        $msg->setContenu($contenu);
        $msg->setEmetteur($this->getUser());
        $msg->setDestinataire($this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($user));
        $msg->setDate(new \DateTime("now"));
        $em = $this->getDoctrine()->getEntityManager();

        $em->persist($msg);
        $em->flush();

        $response = new Response();

        $str = '<div class="alert alert-success"> Votre message a été envoyé! </div>';
        $response->setContent($str);

        return $response;

    }
    

}
