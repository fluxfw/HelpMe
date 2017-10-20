<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/class.ilHelpMePlugin.php";

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
	 * @return string
	 */
	function getBody() {
		$tpl = $this->pl->getTemplate("il_help_me_email_body.html", true, true);

		$titles = [
			"srsu_title" => $this->title,
			"srsu_email_address" => $this->email,
			"srsu_phone" => $this->phone,
			"srsu_priority" => $this->priority->getPriority(),
			"srsu_description" => $this->description,
			"srsu_reproduce_steps" => $this->reproduce_steps
		];

		foreach ($titles as $title => $txt) {
			$tpl->setCurrentBlock("il_help_me_email_body");

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


	/**
	 * @return array[]
	 */
	public function getScreenshots() {
		return $this->screenshots;
	}


	/**
	 * @param array[] $screenshot
	 */
	public function setScreenshots($screenshots) {
		$this->screenshots = $screenshots;
	}
}
