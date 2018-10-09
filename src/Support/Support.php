<?php

namespace srag\Plugins\HelpMe\Support;

use HelpMeSupportGUI;
use ilDatePresentation;
use ilDateTime;
use ilHelpMePlugin;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Support
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Support {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
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
	 * @var string
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
	 * Support constructor
	 */
	public function __construct() {

	}


	/**
	 * Generate email subject
	 *
	 * @return string
	 */
	public function getSubject(): string {
		return $this->priority . " - " . $this->title;
	}


	/**
	 * Generate email body
	 *
	 * @param string $template email|jira
	 *
	 * @return string
	 */
	public function getBody(string $template): string {
		$tpl = self::plugin()->template("il_help_me_" . $template . "_body.html");

		$fields = [
			"title" => $this->title,
			"name" => $this->name,
			"login" => $this->login,
			"email_address" => $this->email,
			"phone" => $this->phone,
			"priority" => $this->priority,
			"description" => $this->description,
			"reproduce_steps" => $this->reproduce_steps,
			"system_infos" => $this->system_infos,
			"datetime" => $this->getFormatedTime()
		];

		foreach ($fields as $title => $txt) {
			$tpl->setCurrentBlock("il_help_me_body");

			$tpl->setVariable("TITLE", self::plugin()->translate($title, HelpMeSupportGUI::LANG_MODULE_SUPPORT));

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
	public function addScreenshot(array $screenshot)/*: void*/ {
		$this->screenshots[] = $screenshot;
	}


	/**
	 * Format time
	 *
	 * @return string
	 */
	public function getFormatedTime(): string {
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
	public function getTime(): int {
		return $this->time;
	}


	/**
	 * @param int $time
	 */
	public function setTime(int $time)/*: void*/ {
		$this->time = $time;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}


	/**
	 * @param string $title
	 */
	public function setTitle(string $title)/*: void*/ {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName(string $name)/*: void*/ {
		$this->name = $name;
	}


	/**
	 * @return string
	 */
	public function getLogin(): string {
		return $this->login;
	}


	/**
	 * @param string $login
	 */
	public function setLogin(string $login)/*: void*/ {
		$this->login = $login;
	}


	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}


	/**
	 * @param string $email
	 */
	public function setEmail(string $email)/*: void*/ {
		$this->email = $email;
	}


	/**
	 * @return string
	 */
	public function getPhone(): string {
		return $this->phone;
	}


	/**
	 * @param string $phone
	 */
	public function setPhone(string $phone)/*: void*/ {
		$this->phone = $phone;
	}


	/**
	 * @return string
	 */
	public function getPriority(): string {
		return $this->priority;
	}


	/**
	 * @param string $priority
	 */
	public function setPriority(string $priority)/*: void*/ {
		$this->priority = $priority;
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}


	/**
	 * @param string $description
	 */
	public function setDescription(string $description)/*: void*/ {
		$this->description = $description;
	}


	/**
	 * @return string
	 */
	public function getReproduceSteps(): string {
		return $this->reproduce_steps;
	}


	/**
	 * @param string $reproduce_steps
	 */
	public function setReproduceSteps(string $reproduce_steps)/*: void*/ {
		$this->reproduce_steps = $reproduce_steps;
	}


	/**
	 * @return string
	 */
	public function getSystemInfos(): string {
		return $this->system_infos;
	}


	/**
	 * @param string $system_infos
	 */
	public function setSystemInfos(string $system_infos)/*: void*/ {
		$this->system_infos = $system_infos;
	}


	/**
	 * @return array[]
	 */
	public function getScreenshots(): array {
		return $this->screenshots;
	}


	/**
	 * @param array[] $screenshots
	 */
	public function setScreenshots(array $screenshots)/*: void*/ {
		$this->screenshots = $screenshots;
	}
}
