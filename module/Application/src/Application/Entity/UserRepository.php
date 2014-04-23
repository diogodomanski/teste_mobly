<?php

namespace Application\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of UserRepository
 *
 * @author domanski
 */
class UserRepository extends EntityRepository {
	public function getFindAllQuery($term = null) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->orderBy('e.name', 'ASC');

		if (!empty($term) && trim($term) != '')
			$qb->where("e.name LIKE :term")->setParameter('term', "%{$term}%");

		return $qb;
	}

	public function findByEmailAndPassword($email, $password) {
		return $this->findOneBy(array("email" => $email, "password" => sha1($password)));
	}
	
	public function findUnique(\Application\Entity\User $entity) {
		$qb = $this->getEntityManager()->createQueryBuilder();

		$qb->select('e')
				->from($this->getEntityName(), 'e')
				->where('e.email = :email')->setParameter("email", $entity->getEmail());

		if ($entity->getId() > 0)
			$qb->andWhere("e.id != :id")->setParameter('id', $entity->getId());


		return $qb->getQuery()->getOneOrNullResult();
	}

}
