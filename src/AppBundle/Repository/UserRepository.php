<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class UserRepository extends EntityRepository implements UserLoaderInterface{


    public function createFindAllQuery(){
        return $this->_em->createQuery(
            "
            SELECT u.id, u.username
            FROM AppBundle:User u
            "
        );
    }

    public function createFindFriendsQuery($id)
    {

        $query = $this->_em->createQuery(
            "select u.username, u.id from AppBundle:User u where :id member of u.friends"
        );

        $query->setParameter('id', $id);

        return $query;
    }

    public function createFindInfoQuery($id){
        $query = $this->_em->createQuery(
            "select u.username, u.race, u.age, u.planet, u.status from AppBundle:User u where u.id = :id"
        );
        $query->setParameter('id', $id);

        return $query;
    }

    public function loadUserByEmail($email) {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadUserByUsername($username) {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
