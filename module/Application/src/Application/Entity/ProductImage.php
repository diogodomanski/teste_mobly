<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Criteria;
use Zend\Stdlib\Hydrator;

/**
 * Description of ProductImage
 *
 * @ORM\Entity(repositoryClass="Application\Entity\ProductImageRepository")
 * @ORM\Table(name="product_product_image")
 * @author domanski
 */
class ProductImage {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Product", inversedBy="images")
	 * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
	 * @var \Application\Entity\Product
	 */
	private $product;
	
	/**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $title;
	
	/**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $bytes;
	
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
	 * @return int
	 */
	public function getId() {
		return $this->id;
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
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * 
	 * @return string Base64 representation
	 */
	public function getBytes() {
		return $this->bytes;
	}

	/**
	 * 
	 * @param int $id
	 * @return \Application\Entity\ProductImage
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * 
	 * @param \Application\Entity\Product $product
	 * @return \Application\Entity\ProductImage
	 */
	public function setProduct(\Application\Entity\Product $product) {
		$this->product = $product;
		return $this;
	}
	
	/**
	 * 
	 * @param string $title
	 * @return \Application\Entity\ProductImage
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
	
	/**
	 * 
	 * @param string $bytes Base64 image representation
	 * @return \Application\Entity\ProductImage
	 */
	public function setBytes($bytes) {
		$this->bytes = $bytes;
		return $this;
	}

	public function toVO() {
		$result = array(
			'id' => $this->getId(),
			'product_id' => $this->getProduct()->getId(),
			'product_name' => $this->getProduct()->getName(),
			'title' => $this->getTitle(),
			'bytes' => $this->getBytes()
		);

		return $result;
	}
}
