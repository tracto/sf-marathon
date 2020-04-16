<?php

namespace TdS\MarathonBundle\Entity;

use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\Theme;
use TdS\MarathonBundle\Entity\Saison;
use Doctrine\ORM\EntityRepository;


/**
 * MusicTitleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MusicTitleRepository extends \Doctrine\ORM\EntityRepository
{
	public function getDixieme(Theme $theme){
		$queryBuilder=$this->_em->createQueryBuilder()
			->addselect('a','j','ji')
			->where('a.theme = :theme')
       		->setParameter('theme', $theme)
       		->orderBy('a.dateUpload', 'ASC')
       		->from($this->_entityName,'a')
       		->leftjoin('a.joggeur','j')
       		->leftjoin('j.image','ji')       		
       		->setFirstResult(9)
   			->setMaxResults(1);

		return $queryBuilder->getQuery()->getOneOrNullResult();
	}


	public function findAllBySaison(Saison $saison){
		$themesId = array();
  		foreach ($saison->getThemes() as $theme) {
      		$themesId[] = $theme->getId();
  		}
      

      $queryBuilder = $this->createQueryBuilder('c') 
          ->addSelect('c','j','t','it','ij')
          ->leftJoin('c.theme','t')
          ->where('t.id IN (:id)')
          ->setParameter('id', $themesId)
          ->leftJoin('t.image', 'it')
          ->leftJoin('c.joggeur', 'j') 
          ->leftJoin('j.image', 'ij')         
          ;
                   
          
  		return $queryBuilder
       		->getQuery()
       		->getResult();
	}



	public function getcountByTheme(Theme $theme){
			$queryBuilder =$this->_em->createQueryBuilder()
				->select("COUNT(a.id) as totalTitle")
				->from($this->_entityName,'a')
				->where('a.theme = :theme')
				   ->setParameter('theme', $theme);
			return $queryBuilder->getQuery()->getOneOrNullResult();
	}




	public function getFirst(Theme $theme){
		$queryBuilder=$this->_em->createQueryBuilder()
			->select('a')
			->where('a.theme = :theme')
       		->setParameter('theme', $theme)
       		->orderBy('a.dateUpload', 'ASC')
       		->setFirstResult(0)
   			->setMaxResults(1)
			->from($this->_entityName,'a');


		return $queryBuilder
    		->getQuery()
    		->getResult();
	}


	public function findAllDurations(){
		$queryBuilder =$this->_em->createQueryBuilder()
			->select("SUM(a.duration) * 10 / 3600 as kilometrage, SUM(a.duration) as temps")
			->from($this->_entityName,'a');
		return $queryBuilder->getQuery()->getOneOrNullResult();
	}

}
