<?php
/**
 * Created by PhpStorm.
 * User: aym3ntn
 * Date: 11/08/2014
 * Time: 17:17
 */

namespace BestTrip\UserBundle\DataFixtures\ORM;


use BestTrip\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        /* ROLE ADMIN */
        $user1 = $userManager->createUser();

        $user1->setNom('Ben Tanfous');
        $user1->setPrenom('Admin');

        $user1->setUsername('admin');
        $user1->setPassword($this->encodePassword($user1, 'admin'));
        $user1->setEmail('besttrip.admin@gmail.com');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setEnabled(1);

        $userManager->updateUser($user1);


        /* ROLE USER */
        $user1 = $userManager->createUser();

        $user1->setNom('Ben Tanfous');
        $user1->setPrenom('Aymen');

        $user1->setUsername('aymen');
        $user1->setPassword($this->encodePassword($user1, 'aymen'));
        $user1->setEmail('aymen@gmail.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setEnabled(1);

        $userManager->updateUser($user1);

        /* ROLE USER */
        $user1 = $userManager->createUser();

        $user1->setNom('Ben Tanfous');
        $user1->setPrenom('Oussama');

        $user1->setUsername('oussama');
        $user1->setPassword($this->encodePassword($user1, 'oussama'));
        $user1->setEmail('oussama@gmail.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setEnabled(1);

        $userManager->updateUser($user1);

        /* ROLE USER */
        $user1 = $userManager->createUser();

        $user1->setNom('Ben Tanfous');
        $user1->setPrenom('Soumaya');

        $user1->setUsername('soumaya');
        $user1->setPassword($this->encodePassword($user1, 'soumaya'));
        $user1->setEmail('soumaya@gmail.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setEnabled(1);

        $userManager->updateUser($user1);

        /* ROLE USER */
        $user1 = $userManager->createUser();

        $user1->setNom('Chtioui');
        $user1->setPrenom('Mehdi');

        $user1->setUsername('mehdi');
        $user1->setPassword($this->encodePassword($user1, 'mehdi'));
        $user1->setEmail('mehdi@gmail.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setEnabled(1);

        $userManager->updateUser($user1);



    }

    private function encodePassword($user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')
            ->getEncoder($user)
        ;

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder(){
        return 20;
    }
}