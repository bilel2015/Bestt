<?php

namespace BestTrip\UserBundle\Controller;

use BestTrip\UserBundle\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsletterController extends Controller
{


    public function redirectionAction()
    {
        return $this->render('UserBundle:Newsletter:formMail.html.twig');
    }

    public function sendMailAction()
    {
        $Request = $this->getRequest();
        if ($Request->getMethod() == "POST") {
            $Subject = $Request->get("Subject");
            $emails = $this->getDoctrine()->getManager()->getRepository('UserBundle:Newsletter')->findAll();
            $message = $Request->get("message");
            foreach ($emails as $email) {
                $mailer = $this->container->get('mailer');
                $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                    ->setUsername('******')
                    ->setPassword('********');
                $mailer = \Swift_Mailer::newInstance($transport);
                $message = \Swift_Message::newInstance('Test')
                    ->setSubject($Subject)
                    ->setFrom('bilel.laifi@esprit.tn')
                    ->setTo($email->getMail())
                    ->setBody($message);
                $this->get('mailer')->send($message);
            }
        }


        return $this->render('UserBundle:Newsletter:formMail.html.twig');
    }

    public function subscribeAction()
    {
        $newsletter = new Newsletter();
        $Request = $this->getRequest();
        if ($Request->getMethod() == 'POST') {
            $email = $Request->get('email');
            $newsletter->setMail($email);


            $em = $this->getDoctrine()->getManager();
            $newsletter1 = $em->getRepository('UserBundle:Newsletter')->find($email);
            if (!$newsletter1) {
                $em->persist($newsletter);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('UserBundle:Newsletter:FormNewsletter.html.twig');
    }

}
