<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class IndexController extends AbstractActionController {

	public function indexAction() {
		return new ViewModel();
	}

	public function listCategoriesAction() {
		$em =  $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo = $em->getRepository('Application\Entity\Category');
		
		$entities = $repo->findAll();
		
		$result = array();
		foreach($entities as $entity) {
			$result[] = $entity->toVO();
		}
		
		$response = new \Application\Response\BaseResponse();
		$response->setCode(0)
				->setData($result)
				->setMessage("");
			
		return new \Zend\View\Model\JsonModel($response);
	}

	public function listProductsAction() {
		$categoryId = $this->params()->fromRoute('id', null);
		
		$em =  $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo = $em->getRepository('Application\Entity\Product');
		
		if($categoryId > 0)
			$entities = $repo->findByCategoryId($categoryId);
		else
			$entities = $repo->findAll();
		
		$result = array();
		foreach($entities as $entity) {
			$result[] = $entity->toVO();
		}

		$response = new \Application\Response\BaseResponse();
		$response->setCode(0)
				->setData($result)
				->setMessage("");
			
		return new \Zend\View\Model\JsonModel($response);
	}

	public function getProductDetailAction() {
		$productId = $this->params()->fromRoute('id', null);
		
		$em =  $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo = $em->getRepository('Application\Entity\Product');
		
		$entity = $repo->find($productId);
			
		$response = new \Application\Response\BaseResponse();
		$response->setCode(0)
				->setData($entity->toVO())
				->setMessage("");
			
		return new \Zend\View\Model\JsonModel($response);
	}
	
	public function getSessionDataAction() {
		$storage = Container::getDefaultManager()->getStorage();
		$shoppingCart = $storage->offsetGet('shoppingCart');
		$currentUser = $storage->offsetGet('currentUser');
		
		$result = array(
			'shopping_cart' => empty($shoppingCart)?array():$shoppingCart,
			'current_user' => empty($currentUser)?null:$currentUser
		);
		
		$response = new \Application\Response\BaseResponse();
		$response->setCode(0)
				->setData($result)
				->setMessage("");
			
		return new \Zend\View\Model\JsonModel($response);
	}
	
	public function saveShoppingCartAction() {
		$params = json_decode($this->getRequest()->getContent(), true);
		$shoppingCart = $params['shopping_cart'];
		
		$storage = Container::getDefaultManager()->getStorage();
		$storage->offsetSet('shoppingCart', $shoppingCart);

		return $this->getSessionDataAction();
	}
	
	public function loginAction() {
		$params = json_decode($this->getRequest()->getContent(), true);
		$email = $params['email'];
		$password = $params['password'];
		
		$em =  $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo = $em->getRepository('Application\Entity\User');
		
		$entity = $repo->findByEmailAndPassword($email, $password);
		
		$storage = Container::getDefaultManager()->getStorage();
		
		$code = 0;
		$message = "";
		$data = null;
		
		if($entity) {
			$storage->offsetSet('currentUser', $entity->toVO());
			$data = $entity->toVO();
		} else {
			$storage->offsetUnset('currentUser');
			$code = 1;
			$message = "Invalid login";
		}

		$response = new \Application\Response\BaseResponse();
		$response->setCode($code)
				->setData($data)
				->setMessage($message);
			
		return new \Zend\View\Model\JsonModel($response);
	}
	
	public function getProductImageAction() {
		$id = $this->params()->fromRoute("id");
		
		if(empty($id)) {
			$this->getResponse()->setStatusCode(404);
			return new ViewModel();
		}
		
		$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo = $em->getRepository('Application\Entity\ProductImage');
		
		$entity = $repo->find($id);
		
		if(empty($entity)) {
			$this->getResponse()->setStatusCode(404);
			return new ViewModel();
		}
				
		$imageContent = $entity->getBytes();
		
		$response = $this->getResponse();
		$response->setContent(base64_decode($imageContent));
		$response
			->getHeaders()
			->addHeaderLine('Content-Transfer-Encoding', 'binary')
			->addHeaderLine('Content-Type', 'image/jpg');
		
		return $response;
	}
	
	public function placeOrderAction() {
		$storage = Container::getDefaultManager()->getStorage();
		$shoppingCart = $storage->offsetGet('shoppingCart');
		$currentUser = $storage->offsetGet('currentUser');
		
		$viewModel = new \Zend\View\Model\JsonModel();
		
		if(empty($shoppingCart)) {
			$this->getResponse()->setStatusCode(403);
			$viewModel->setVariables(array("error" => 1, "message" => "Empty shopping cart"));
			return $viewModel;
		}
		
		if(empty($currentUser)) {
			$this->getResponse()->setStatusCode(403);
			$viewModel->setVariables(array("error" => 2, "message" => "You must sign in first"));
			return $viewModel;
		}
		
		$total = 0;
		foreach($shoppingCart as $item) {
			$total += $item['total'];
		}
		
		$order = array(
			"items" => $shoppingCart,
			"user_id" => $currentUser['id'],
			"total" => $total
		);
		
		$orderService = $this->getServiceLocator()->get('Application\Service\Order');
		$orderEntity = $orderService->insert($order);
		
		if(empty($orderEntity)) {
			$this->getResponse()->setStatusCode(403);
			$viewModel->setVariables(array("error" => 3, "message" => "Order could not be saved"));
			return $viewModel;
		}
		
		$storage->offsetUnset("shoppingCart");
		
		$response = new \Application\Response\BaseResponse();
		$response->setCode(0)
				->setData($orderEntity->toVO())
				->setMessage("");
			
		return new \Zend\View\Model\JsonModel($response);
	}

	public function getOrdersAction() {
		$storage = Container::getDefaultManager()->getStorage();
		$currentUser = $storage->offsetGet('currentUser');
		
		$viewModel = new \Zend\View\Model\JsonModel();
		
		if(empty($currentUser)) {
			$this->getResponse()->setStatusCode(403);
			$viewModel->setVariables(array("error" => 2, "message" => "You must sign in first"));
			return $viewModel;
		}
		
		$em =  $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$repo = $em->getRepository('Application\Entity\Order');
		
		$orders = $repo->findByUserId($currentUser['id']);
		
		$result = array();
		
		foreach($orders as $order) {
			$result[] = $order->toVO();
		}
		
		$response = new \Application\Response\BaseResponse();
		$response->setCode(0)
				->setData($result)
				->setMessage("");
			
		return new \Zend\View\Model\JsonModel($response);
	}
}
