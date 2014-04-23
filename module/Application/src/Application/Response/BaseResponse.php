<?php

namespace Application\Response;

/**
 * Description of BaseResponse
 *
 * @author domanski
 */
class BaseResponse extends AbstractResponse {

	/**
	 *
	 * @var int
	 */
	private $code;

	/**
	 *
	 * @var string
	 */
	private $message;

	/**
	 *
	 * @var array
	 */
	private $data;

	/**
	 * 
	 * @return int
	 */
	public function getCode() {
		return $this->code;
	}

	/**
	 * 
	 * @param int $code
	 * @return \Application\Response\BaseResponse
	 */
	public function setCode($code) {
		$this->code = $code;
		return $this;
	}

	/**
	 * 
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * 
	 * @param string $message
	 * @return \Application\Response\BaseResponse
	 */
	public function setMessage($message) {
		$this->message = $message;
		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * 
	 * @param array $data
	 * @return \Application\Response\BaseResponse
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}

}
