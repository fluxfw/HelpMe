<?php

namespace srag\Plugins\HelpMe\Support;

use ilDatePresentation;
use ilDateTime;
use ilHelpMePlugin;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\HelpMeConfigPriority;

/**
 * Class HelpMeSupport
 *
 * @package srag\Plugins\HelpMe\Support
 */
class HelpMeSupport {

	use DICTrait;
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
	 * @var HelpMeConfigPriority
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
	 * HelpMeSupport constructor
	 */
	public function __construct() {

	}


	/**
	 * Generate email subject
	 *
	 * @return string
	 */
	public function getSubject(): string {
		return $this->priority->getPriority() . " - " . $this->title;
	}


	/**
	 * Generate email body
	 *
	 * @param string $template email|jira
	 *
	 * @return string
	 */
	public function getBody(string $template): string {
		$tpl = self::template("il_help_me_" . $template . "_body.html");

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

			$tpl->setVariable("TITLE", self::translate($title));

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
	public function addScreenshot(array $screenshot) {
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
	public function setTime(int $time) {
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
	public function setTitle(string $title) {
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
	public function setName(string $name) {
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
	public function setLogin(string $login) {
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
	public function setEmail(string $email) {
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
	public function setPhone(string $phone) {
		$this->phone = $phone;
	}


	/**
	 * @return HelpMeConfigPriority
	 */
	public function getPriority(): HelpMeConfigPriority {
		return $this->priority;
	}


	/**
	 * @param HelpMeConfigPriority $priority
	 */
	public function setPriority(HelpMeConfigPriority $priority) {
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
	public function setDescription(string $description) {
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
	public function setReproduceSteps(string $reproduce_steps) {
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
	public function setSystemInfos(string $system_infos) {
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
	public function setScreenshots(array $screenshots) {
		$this->screenshots = $screenshots;
	}
}
