<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Criteria;
use Zend\Stdlib\Hydrator;

/**
 * Description of OrderItem
 *
 * @ORM\Entity(repositoryClass="Application\Entity\OrderItemRepository")
 * @ORM\Table(name="order_order_item")
 * @ORM\HasLifecycleCallbacks
 * @author domanski
 */
class OrderItem {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Order")
	 * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
	 * @var \Application\Entity\Order
	 */
	private $order;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Product")
	 * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
	 * @var \Application\Entity\Product
	 */
	private $product;
	
	/**
     * @var float
     *
     * @ORM\Column(type="decimal")
     */
    private $price;
	
	/**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $count;
	
	/**
     * @var float
     *
     * @ORM\Column(type="decimal")
     */
    private $total;
	
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
			// order
			if(isset($options['order']))
				$options['order'] = $em->getReference('Application\Entity\Order', $options['order']);
			else if(isset($options['order_id']))
				$options['order'] = $em->getReference('Application\Entity\Order', $options['order_id']);
			
			// product
			if(isset($options['product']))
				$options['product'] = $em->getReference('Application\Entity\Product', $options['product']);
			else if(isset($options['product_id']))
				$options['product'] = $em->getReference('Application\Entity\Product', $options['product_id']);
		}
		
		$hydrator = new Hydrator\ClassMethods();
		$hydrator->hydrate($options, $this);
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * 
	 * @return \Application\Entity\Order
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * 
	 * @return \Application\Entity\Product
	 */
	public function getProduct() {
		return $this->product;
	}

	/**
	 * 
	 * @return float
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * 
	 * @return int
	 */
	public function getCount() {
		return $this->count;
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
	 * @return \Application\Entity\OrderItem
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * 
	 * @param \Application\Entity\Order $order
	 * @return \Application\Entity\OrderItem
	 */
	public function setOrder(\Application\Entity\Order $order) {
		$this->order = $order;
		return $this;
	}

	/**
	 * 
	 * @param \Application\Entity\Product $product
	 * @return \Application\Entity\OrderItem
	 */
	public function setProduct(\Application\Entity\Product $product) {
		$this->product = $product;
		return $this;
	}

	/**
	 * 
	 * @param float $price
	 * @return \Application\Entity\OrderItem
	 */
	public function setPrice($price) {
		$this->price = $price;
		return $this;
	}

	/**
	 * 
	 * @param int $count
	 * @return \Application\Entity\OrderItem
	 */
	public function setCount($count) {
		$this->count = $count;
		return $this;
	}

	/**
	 * @ORM\PrePersist
	 * @ORM\PreFlush
	 * @return \Application\Entity\OrderItem
	 */
	public function setTotal() {
		$this->total = $this->getPrice() * $this->getCount();
		return $this;
	}

	
	public function toVO() {
		$result = array(
			'id' => $this->getId(),
			'order_id' => $this->getOrder()->getId(),
			'product_id' => $this->getProduct()->getId(),
			'product_name' => $this->getProduct()->getName(),
			'price' => $this->getPrice(),
			'count' => $this->getCount(),
			'total' => $this->getTotal()
		);

		return $result;
	}
}
