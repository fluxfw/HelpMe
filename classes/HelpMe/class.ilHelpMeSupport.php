<?php

/**
 * Support data
 */
class ilHelpMeSupport {

	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $email;
	/**
	 * @var string
	 */
	protected $phone;
	/**
	 * @var ilHelpMeConfigPriority
	 */
	protected $priority;
	/**
	 * @var string
	 */
	protected $description;
	/**
	 * @var string
	 */
	protected $reproduce_steps;


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}


	/**
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}


	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}


	/**
	 * @param string $phone
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}


	/**
	 * @return ilHelpMeConfigPriority
	 */
	public function getPriority() {
		return $this->priority;
	}


	/**
	 * @param ilHelpMeConfigPriority $priority
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}


	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}


	/**
	 * @return string
	 */
	public function getReproduceSteps() {
		return $this->reproduce_steps;
	}


	/**
	 * @param string $reproduce_steps
	 */
	public function setReproduceSteps($reproduce_steps) {
		$this->reproduce_steps = $reproduce_steps;
	}
}
