<?php

namespace TdS\MarathonBundle\Entity;

use TdS\UserBundle\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * JoggeurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JoggeurRepository extends \Doctrine\ORM\EntityRepository{
	public function findAll(){
        return $this->findBy(array(), array('pseudo' => 'ASC'));
    }


    public function findAllOnlyId(){
        $queryBuilder = $this->createQueryBuilder('j')
            ->addSelect('j') 
            // 'partial j.{id,pseudo}'
            ->orderBy('j.id', 'ASC');

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }




    public function findAllSortByLastLogin(){
    	$queryBuilder = $this->createQueryBuilder('c')
        	->addSelect('c','partial m.{id}','partial mt.{id}','i')
        	->leftJoin('c.user', 'm')
          ->leftJoin('c.image', 'i')
          ->leftJoin('c.musicTitles','mt')
        	->orderBy('m.lastLogin', 'DESC');

        return $queryBuilder
     		->getQuery()
     		->getResult();
    }



    public function findAllJoggeursWithUsers(){
        $queryBuilder = $this->createQueryBuilder('c')
          ->addSelect('c','u')
          ->leftJoin('c.user', 'u');
          

        return $queryBuilder
        ->getQuery()
        ->getResult();
    }


    public function findJoggeurById($id){
        $results = $this->createQueryBuilder('j')
          ->addSelect('j','i','m','mt','mti','u','js','jss')
          ->leftJoin('j.image','i')
          ->leftJoin('j.musicTitles','m')
          ->leftJoin('m.theme','mt')
          ->leftJoin('mt.image','mti')
          ->leftJoin('j.user','u')
          ->leftJoin('j.joggeurScore','js')
          ->leftJoin('js.scores','jss')
          ->where('j.id = :id')
          ->setParameter('id', $id)
          ->getQuery()->getOneOrNullResult();
 
        return $results;
    }


    public function findJoggeurByUser(User $user){
        $userid=$user->getId();

        $results = $this->createQueryBuilder('j')
          ->addSelect('j','i','u','js','jss')
          ->leftJoin('j.image','i')
          ->leftJoin('j.user','u')
          ->leftJoin('j.joggeurScore','js')
          ->leftJoin('js.scores','jss')
          ->where('u.id = :id')
          ->setParameter('id', $userid)
          ->getQuery()->getOneOrNullResult();
 
        return $results;
    }

}