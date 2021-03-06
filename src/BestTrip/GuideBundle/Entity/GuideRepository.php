<?php

namespace BestTrip\GuideBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GuideRepository extends EntityRepository
{
    public function findByCritere($critere){
        $query = $this->createQueryBuilder('g');
        if(count($critere) != 0){
            if($critere['description'] != ''){
                $query->andWhere('g.description LIKE :d')
                    ->setParameter('d', '%'.$critere['description'].'%');
            }
            if($critere['rating'] != ''){
                $query->andWhere('g.rating between :r and :r1')
                    ->setParameter('r',$critere['rating'])
                    ->setParameter('r1',$critere['rating']+1);
            }
            if($critere['titre'] != ''){
                $query->andWhere('g.titre LIKE :t')
                    ->setParameter('t', '%'.$critere['titre'].'%');
            }
            if($critere['pays'] != ''){
                $query->join('g.pays','p','WITH', 'g.pays = p.id')->andWhere('p.nom LIKE :c')
                    ->setParameter('c',$critere['pays'].'%');
            }
        }
        $query->orderBy('g.dateCreation');
        $query->andWhere('g.valid = 1');

        return $query->getQuery()->getResult();
    }

    public function findByCritereAndUser($critere, $user){
        $query = $this->createQueryBuilder('g');
        if(count($critere) != 0){
            if($critere['description'] != ''){
                $query->andWhere('g.description LIKE :d')
                    ->setParameter('d', '%'.$critere['description'].'%');
            }
            if($critere['rating'] != ''){
                $query->andWhere('g.rating between :r and :r1')
                    ->setParameter('r',$critere['rating'])
                    ->setParameter('r1',$critere['rating']+1);
            }
            if($critere['titre'] != ''){
                $query->andWhere('g.titre LIKE :t')
                    ->setParameter('t', '%'.$critere['titre'].'%');
            }
            if($critere['pays'] != ''){
                $query->join('g.pays','p','WITH', 'g.pays = p.id')->andWhere('p.nom LIKE :c')
                    ->setParameter('c',$critere['pays'].'%');
            }
        }
        $query->orderBy('g.dateCreation');

        $query->join('g.user','u','WITH', 'u.id = :id')
            ->setParameter('id',$user->getId());

        return $query->getQuery()->getResult();
    }
}
