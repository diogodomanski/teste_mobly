<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of OrderRepository
 *
 * @author domanski
 */
class OrderRepository extends EntityRepository {

	public function findByUserId($userId) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->innerJoin('e.user', 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'u.id = :user_id')->setParameter('user_id', $userId);
		
		return $qb->getQuery()->getResult();
	}

}
