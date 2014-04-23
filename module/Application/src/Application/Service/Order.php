<?php

namespace Application\Service;

/**
 * Description of Order
 *
 * @author domanski
 */
class Order extends AbstractService {

	public function __construct(\Doctrine\ORM\EntityManager $em) {
		parent::__construct($em);
		$this->entityName = "Application\Entity\Order";
	}
	
	public function insert(array $data) {
		$orderEntity = parent::insert($data);
		
		$orderItemService = new OrderItem($this->em);
		
		foreach($data['items'] as $orderItem) {
			$orderItem['order_id'] = $orderEntity->getId();
			$orderItemService->insert($orderItem);
		}
		
		return $orderEntity;
	}

	public function validate(\Application\Entity\Order &$entity) {
		if (!$entity->getUser())
			throw new \Exception("[{$this->entityName}] User is mandatory");

//		if (!$entity->getItems())
//			throw new \Exception("[{$this->entityName}] Order must have at least one item");
	}

}
