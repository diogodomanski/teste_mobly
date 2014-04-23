<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoryRepository
 *
 * @author domanski
 */
class CategoryRepository extends EntityRepository {
	public function getFindAllQuery($term = null) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->orderBy('e.name', 'ASC');

		if (!empty($term) && trim($term) != '')
			$qb->where("e.name LIKE :term")->setParameter('term', "%{$term}%");

		return $qb;
	}

	public function findByOrderId($orderId) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->innerJoin('e.order', 'o', \Doctrine\ORM\Query\Expr\Join::WITH, 'o.id = :order_id')->setParameter('order_id', $orderId);
		
		return $qb->getQuery()->getResult();
	}
	
	public function findUnique(\Application\Entity\OrderItem $entity) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->innerJoin('e.product', 'product', \Doctrine\ORM\Query\Expr\Join::WITH, 'product.id = :product_id')->setParameter('product_id', $entity->getProduct()->getId());

		if ($entity->getId() > 0)
			$qb->andWhere("e.id != :id")->setParameter('id', $entity->getId());


		return $qb->getQuery()->getOneOrNullResult();
	}

}
