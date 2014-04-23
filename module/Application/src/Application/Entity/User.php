<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator;

/**
 * Description of User
 *
 * @ORM\Entity(repositoryClass="Application\Entity\UserRepository")
 * @ORM\Table(name="user_user")
 * @author domanski
 */
class User {
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
	protected $email;
	
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $password;
	
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	protected $address;
	
	
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
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * 
	 * @param int $id
	 * @return \Application\Entity\User
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * 
	 * @param string $name
	 * @return \Application\Entity\User
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * 
	 * @param string $email
	 * @return \Application\Entity\User
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	
	/**
	 * 
	 * @param string $password
	 * @return \Application\Entity\User
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	
	/**
	 * 
	 * @param string $address
	 * @return \Application\Entity\User
	 */
	public function setAddress($address) {
		$this->password = $address;
		return $this;
	}

	public function toVO() {
		$result = array(
			'id' => $this->getId(),
			'name' => $this->getName(),
			'email' => $this->getEmail(),
			'address' => $this->getAddress()
		);

		return $result;
	}
}
