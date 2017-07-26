<?php

namespace BestTrip\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function guideMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Acceuil', array('route' => 'homepage'));
        $menu->addChild('Guides', array('route' => 'guide'));
        return $menu;
    }

    public function guideOwnMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Acceuil', array('route' => 'homepage'));
        $menu->addChild('Guides', array('route' => 'my_guide'));
        return $menu;
    }

    public function guideNewMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Acceuil', array('route' => 'homepage'));
        $menu->addChild('Guides', array('route' => 'guide'))
               ->addChild('Ajouter un guide', array('route' => 'guide_new'));
        return $menu;
    }
    public function guideShowMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Acceuil', array('route' => 'homepage'));
        $id = $this->container->get('request')->get('id');
        $menu->addChild('Guides', array('route' => 'guide'))
        ->addChild('Afficher', array('route' => 'guide_show','routeParameters'=>array('id'=>$id)));
        return $menu;
    }
}