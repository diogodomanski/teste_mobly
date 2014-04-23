<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Criteria;
use Zend\Stdlib\Hydrator;

/**
 * Description of Category
 *
 * @ORM\Entity(repositoryClass="Application\Entity\ProductRepository")
 * @ORM\Table(name="product_product")
 * @author domanski
 */
class Product {
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
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="decimal")
	 * @var float
	 */
	protected $price;
	
	
	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Category", inversedBy="products")
	 * @ORM\JoinTable(name="product_product_category")
	 * 
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	protected $categories;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Feature", inversedBy="products")
	 * @ORM\JoinTable(name="product_product_feature")
	 * 
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	protected $features;
	
	/**
	 * @ORM\OneToMany(targetEntity="Application\Entity\ProductImage", mappedBy="product")
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 */
	private $images;
	
	/**
	 * 
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
		$this->features = new \Doctrine\Common\Collections\ArrayCollection();
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();

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
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
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
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getCategories() {
		return $this->categories;
	}
	
	/**
	 * 
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getFeatures() {
		return $this->features;
	}
	
	/**
	 * 
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * 
	 * @param int $id
	 * @return \Application\Entity\Product
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * 
	 * @param string $name
	 * @return \Application\Entity\Product
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * 
	 * @param string $description
	 * @return \Application\Entity\Product
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	
	/**
	 * 
	 * @param string $price
	 * @return \Application\Entity\Product
	 */
	public function setPrice($price) {
		$this->price = $price;
		return $this;
	}

	public function toVO() {
		$result = array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'price' => $this->getPrice(),
			//'categories' => array()
			'features' => array(),
			'images' => array()
		);

		//foreach ($this->getCategories() as $category) {
		//	$result['categories'][] = $category->toVO();
		//}
		
		foreach ($this->getFeatures() as $feature) {
			$result['features'][] = $feature->toVO();
		}
		
		foreach ($this->getImages() as $image) {
			$result['images'][] = $image->toVO();
		}

		return $result;
	}
}
