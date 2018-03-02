<?php

require_once "Services/Calendar/classes/class.ilDatePresentation.php";
require_once "Services/Calendar/classes/class.ilDateTime.php";

/**
 * Support data
 */
class ilHelpMeSupport {

	/**
	 * @var int
	 */
	protected $time;
	/**
	 * @var string
	 */
	protected $title;
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var string
	 */
	protected $login;
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
	 * @var string
	 */
	protected $system_infos;
	/**
	 * @var array[]
	 */
	protected $screenshots = [];
	/**
	 * @var ilHelpMeUIHookGUI
	 */
	protected $pl;


	public function __construct() {
		$this->pl = ilHelpMePlugin::getInstance();
	}


	/**
	 * Generate email subject
	 *
	 * @return string
	 */
	function getSubject() {
		return $this->priority->getPriority() . " - " . $this->title;
	}


	/**
	 * Generate email body
	 *
	 * @param string $template email|jira
	 *
	 * @return string
	 */
	function getBody($template) {
		$tpl = $this->pl->getTemplate("il_help_me_" . $template . "_body.html", true, true);

		$fields = [
			"srsu_title" => $this->title,
			"srsu_name" => $this->name,
			"srsu_login" => $this->login,
			"srsu_email_address" => $this->email,
			"srsu_phone" => $this->phone,
			"srsu_priority" => $this->priority->getPriority(),
			"srsu_description" => $this->description,
			"srsu_reproduce_steps" => $this->reproduce_steps,
			"srsu_system_infos" => $this->system_infos,
			"srsu_datetime" => $this->getFormatedTime()
		];

		foreach ($fields as $title => $txt) {
			$tpl->setCurrentBlock("il_help_me_body");

			$tpl->setVariable("TITLE", $this->pl->txt($title));

			$tpl->setVariable("TXT", $txt);

			$tpl->parseCurrentBlock();
		};

		$body = $tpl->get();

		return $body;
	}


	/**
	 * Add screenshot from post file upload
	 *
	 * @param array $screenshot
	 */
	function addScreenshot($screenshot) {
		$this->screenshots[] = $screenshot;
	}


	/**
	 * Format time
	 *
	 * @return string
	 */
	function getFormatedTime() {
		// Save and restore old existing useRelativeDates
		$useRelativeDates_ = ilDatePresentation::useRelativeDates();

		ilDatePresentation::setUseRelativeDates(false);

		$formated_time = ilDatePresentation::formatDate(new ilDateTime($this->time, IL_CAL_UNIX));

		// Save and restore old existing useRelativeDates
		ilDatePresentation::setUseRelativeDates($useRelativeDates_);

		return $formated_time;
	}


	/**
	 * @return int
	 */
	public function getTime() {
		return $this->time;
	}


	/**
	 * @param int $time
	 */
	public function setTime($time) {
		$this->time = $time;
	}


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
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getLogin() {
		return $this->login;
	}


	/**
	 * @param string $login
	 */
	public function setLogin($login) {
		$this->login = $login;
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


	/**
	 * @return string
	 */
	public function getSystemInfos() {
		return $this->system_infos;
	}


	/**
	 * @param string $system_infos
	 */
	public function setSystemInfos($system_infos) {
		$this->system_infos = $system_infos;
	}


	/**
	 * @return array[]
	 */
	public function getScreenshots() {
		return $this->screenshots;
	}


	/**
	 * @param array[] $screenshots
	 */
	public function setScreenshots($screenshots) {
		$this->screenshots = $screenshots;
	}
}
