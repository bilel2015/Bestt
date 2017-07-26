<?php

namespace BestTrip\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    public function findByName($nom){
        $query = $this->createQueryBuilder('u');
        $query->where('u.nom LIKE :d')
                ->orWhere('u.prenom LIKE :d')
                    ->setParameter('d', '%'.$nom.'%');
        return $query->getQuery()->getResult();
    }

   
}
