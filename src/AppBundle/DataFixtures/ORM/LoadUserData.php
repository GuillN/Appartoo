<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use AppBundle\Entity\User;
/*use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;*/
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadUserData implements ORMFixtureInterface/*, ContainerAwareInterface */{

    /*private $container;*/

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@admin.com');
        $password = $this->encoder->encodePassword($user, '0000');
        $user->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }

    /*public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }*/
}