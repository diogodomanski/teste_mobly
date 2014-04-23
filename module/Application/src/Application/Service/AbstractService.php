<?php
namespace Application\Service;

use Doctrine\ORM\EntityManager;
use Zend\Stdlib\Hydrator;

/**
 * Description of AbstractService
 *
 * @author domanski
 */
abstract class AbstractService {	
	/**
	 *
	 * @var EntityManager
	 */
	protected $em;
	
	/**
	 *
	 * @var string
	 */
	protected $entityName;
	
	
	public function __construct(EntityManager $em) {
		$this->em = $em;
	}
	
	/**
	 * 
	 * @return \Doctrine\ORM\EntityRepository The repository class
	 */
	public function getRepository() {
		return $this->em->getRepository($this->entityName);
	}
	
	public function insert(array $data) {
		// If entity class has hydrate method
		if(method_exists($this->entityName, 'hydrate')) {
			$entity = new $this->entityName();
			$entity->hydrate($data, $this->em);
		}
		else
			$entity = new $this->entityName($data);
		
		// If entity class has insertDate attribute
		if(method_exists($entity, 'setInsertDate') && $entity->getInsertDate() == null)
			$entity->setInsertDate();
		
		// If entity class has updateDate attribute
		if(method_exists($entity, 'setUpdateDate'))
			$entity->setUpdateDate();
		
		// If entity's service class has validate method
		if(method_exists($this, 'validate'))
			$this->validate($entity);
		
		$this->em->persist($entity);
		$this->em->flush();
		
		return $entity;
	}
	
	public function update(array $data) {
		$entity = $this->em->getReference($this->entityName, $data['id']);
				
		// If entity class has hydrate method
		if(method_exists($this->entityName, 'hydrate'))
			$entity->hydrate($data, $this->em);
		else {
			$hydrator = new Hydrator\ClassMethods();
			$hydrator->hydrate($data, $entity);
		}
		
		// If entity class has updateDate attribute
		if(method_exists($entity, 'setUpdateDate'))
			$entity->setUpdateDate();
		
		// If entity's service class has validate method
		if(method_exists($this, 'validate'))
			$this->validate($entity);
		
		$this->em->persist($entity);
		$this->em->flush();
				
		return $entity;
	}
	
	public function delete($id) {
                
		$entity = $this->em->getReference($this->entityName, $id);
		
		if($entity) {
			if(!method_exists($entity, "getIsSystemRecord") || (method_exists($entity, "getIsSystemRecord") && !$entity->getIsSystemRecord())) {
				if(method_exists($entity, 'setDeleteDate')) {
					$entity->setDeleteDate('now');
					$this->em->persist($entity);
				} else {
					$this->em->remove($entity);
				}

				$this->em->flush();
			}
			
			return $id;
		}
	}
}
