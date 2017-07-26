<?php

namespace BestTrip\SiteBundle\Controller;

use BestTrip\GuideBundle\Entity\Guide;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class SiteController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $guides = $em->getRepository('GuideBundle:Guide')->findBy(array('valid'=> 1), array(), 6, 1);
        $lieux = $em->getRepository('GuideBundle:Lieu')->findAll();

        return $this->render('SiteBundle:Site:index.html.twig', array(
            'guides' => $guides,
            'lieux' => $lieux,
        ));
    }

}
