<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ProductImageRepository
 *
 * @author domanski
 */
class ProductImageRepository extends EntityRepository {
	public function getFindAllQuery($term = null) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->orderBy('e.title', 'ASC');

		if (!empty($term) && trim($term) != '')
			$qb->where("e.title LIKE :term")->setParameter('term', "%{$term}%");

		return $qb;
	}
	
	public function findByProductId($productId) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->innerJoin('e.product', 'product', \Doctrine\ORM\Query\Expr\Join::WITH, 'product.id = :product_id')->setParameter('product_id', $productId);
		
		return $qb->getQuery()->getResult();
	}

	public function findUnique(\Application\Entity\ProductImage $entity) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->where('e.title = :title')->setParameter("title", $entity->getTitle());

		if ($entity->getId() > 0)
			$qb->andWhere("e.id != :id")->setParameter('id', $entity->getId());


		return $qb->getQuery()->getOneOrNullResult();
	}

}
