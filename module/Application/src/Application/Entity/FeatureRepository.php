<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of FeatureRepository
 *
 * @author domanski
 */
class FeatureRepository extends EntityRepository {
	public function getFindAllQuery($term = null) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->orderBy('e.name', 'ASC');

		if (!empty($term) && trim($term) != '')
			$qb->where("e.name LIKE :term")->setParameter('term', "%{$term}%");

		return $qb;
	}

	public function findUnique(\Application\Entity\Feature $entity) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->where('e.name = :name')->setParameter("name", $entity->getName());

		if ($entity->getId() > 0)
			$qb->andWhere("e.id != :id")->setParameter('id', $entity->getId());


		return $qb->getQuery()->getOneOrNullResult();
	}

}
