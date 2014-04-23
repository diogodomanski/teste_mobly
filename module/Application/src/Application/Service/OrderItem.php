<?php

namespace Application\Service;

/**
 * Description of OrderItem
 *
 * @author domanski
 */
class OrderItem extends AbstractService {

	public function __construct(\Doctrine\ORM\EntityManager $em) {
		parent::__construct($em);
		$this->entityName = "Application\Entity\OrderItem";
	}

	public function validate(\Application\Entity\OrderItem &$entity) {
		if (!$entity->getOrder())
			throw new \Exception("[{$this->entityName}] Order is mandatory");
			
		if (!$entity->getProduct())
			throw new \Exception("[{$this->entityName}] Product is mandatory");
			
		if (!$entity->getPrice())
			throw new \Exception("[{$this->entityName}] Price is mandatory");
			
		if (!$entity->getCount())
			throw new \Exception("[{$this->entityName}] Count is mandatory");
	}

}
