<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ProductRepository
 *
 * @author domanski
 */
class ProductRepository extends EntityRepository {
	public function getFindAllQuery($term = null) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->orderBy('e.name', 'ASC');

		if (!empty($term) && trim($term) != '') {
			$qb->where("e.name LIKE :term")
			->orWhere("e.description LIKE :term")->setParameter('term', "%{$term}%");
		}

		return $qb;
	}
	
	public function findByCategoryId($categoryId) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->innerJoin('e.categories', 'category', \Doctrine\ORM\Query\Expr\Join::WITH, 'category.id = :category_id')->setParameter("category_id", $categoryId)
				->orderBy('e.name', 'ASC');

		return $qb->getQuery()->getResult();
	}

	public function findUnique(\Application\Entity\Product $entity) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->where('e.name = :name')->setParameter("name", $entity->getName());

		if ($entity->getId() > 0)
			$qb->andWhere("e.id != :id")->setParameter('id', $entity->getId());


		return $qb->getQuery()->getOneOrNullResult();
	}

}
