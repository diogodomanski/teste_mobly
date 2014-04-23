<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Criteria;
use Zend\Stdlib\Hydrator;

/**
 * Description of Order
 *
 * @ORM\Entity(repositoryClass="Application\Entity\OrderRepository")
 * @ORM\Table(name="order_order")
 * @ORM\HasLifecycleCallbacks
 * @author domanski
 */
class Order {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 * @var \Application\Entity\User
	 */
	private $user;
	
	/**
	 * @ORM\Column(type="datetime", name="insert_date")
	 * @var \DateTime
	 */
	protected $insertDate;
	
	/**
     * @var float
     *
     * @ORM\Column(type="decimal")
     */
    private $total;
	
	/**
	 * @ORM\OneToMany(targetEntity="Application\Entity\OrderItem", mappedBy="order")
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	private $items;
	
	/**
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		if (!empty($options))
			$this->hydrate($options);
	}

	/**
	 * 
	 * @param array $options
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function hydrate(array $options = array(), \Doctrine\ORM\EntityManager $em = null) {
		if (!empty($em)) {
			// user
			if(isset($options['user']))
				$options['user'] = $em->getReference('Application\Entity\User', $options['user']);
			else if(isset($options['user_id']))
				$options['user'] = $em->getReference('Application\Entity\User', $options['user_id']);
		}

		$hydrator = new Hydrator\ClassMethods();
		$hydrator->hydrate($options, $this);
	}
	
	/**
	 * 
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * 
	 * @return \Application\Entity\User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * 
	 * @return \DateTime
	 */
	public function getInsertDate() {
		return $this->insertDate;
	}

	/**
	 * 
	 * @return float
	 */
	public function getTotal() {
		return $this->total;
	}

	/**
	 * 
	 * @param int $id
	 * @return \Application\Entity\Order
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * 
	 * @param \Application\Entity\User $user
	 * @return \Application\Entity\Order
	 */
	public function setUser(\Application\Entity\User $user) {
		$this->user = $user;
		return $this;
	}

	/**
	 * @return \Application\Entity\Order
	 * @ORM\PrePersist
	 */
	public function setInsertDate() {
		$this->insertDate = new \DateTime("now", new \DateTimeZone('UTC'));
		return $this;
	}

	/**
	 * 
	 * @param float $total
	 * @return \Application\Entity\Order
	 */
	public function setTotal($total) {
		$this->total = $total;
		return $this;
	}
	
	/**
	 * 
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getItems() {
		return $this->items;
	}

	public function toVO() {
		$result = array(
			'id' => $this->getId(),
			'user' => $this->getUser()->toVO(),
			'insert_date' => $this->getInsertDate()->getTimestamp(),
			'total' => $this->getTotal(),
			'items' => array()
		);

		foreach ($this->getItems() as $item) {
			$result['items'][] = $item->toVO();
		}

		return $result;
	}
}
