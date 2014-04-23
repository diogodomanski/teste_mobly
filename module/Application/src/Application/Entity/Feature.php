<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Criteria;
use Zend\Stdlib\Hydrator;

/**
 * Description of Feature
 *
 * @ORM\Entity(repositoryClass="Application\Entity\FeatureRepository")
 * @ORM\Table(name="product_category")
 * @author domanski
 */
class Feature {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $name;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Product", mappedBy="features")
	 * 
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	protected $products;
	
	/**
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();

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
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * 
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getProducts() {
		$criteria = Criteria::create()
				->orderBy(array("name" => Criteria::ASC));

		return $this->products->matching($criteria);
	}

	/**
	 * 
	 * @param int $id
	 * @return \Application\Entity\Feature
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * 
	 * @param string $name
	 * @return \Application\Entity\Feature
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function toVO() {
		$result = array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			//'products' => array()
		);

		//foreach ($this->getProducts() as $product) {
		//	$result['products'][] = $product->toVO();
		//}

		return $result;
	}
}
