<?php

namespace srag\Plugins\HelpMe\Support;

use ilDatePresentation;
use ilDateTime;
use ilHelpMePlugin;
use ILIAS\FileUpload\DTO\UploadResult;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Project\Project;
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
	protected $page_reference = "";
	/**
	 * @var Project
	 */
	protected $project;
	/**
	 * @var string
	 */
	protected $issue_type = "";
	/**
	 * @var string
	 */
	protected $fix_version = "";
	/**
	 * @var string
	 */
	protected $title = "";
	/**
	 * @var string
	 */
	protected $name = "";
	/**
	 * @var string
	 */
	protected $login = "";
	/**
	 * @var string
	 */
	protected $email = "";
	/**
	 * @var string
	 */
	protected $phone = "";
	/**
	 * @var string
	 */
	protected $priority = "";
	/**
	 * @var string
	 */
	protected $description = "";
	/**
	 * @var string
	 */
	protected $reproduce_steps = "";
	/**
	 * @var string
	 */
	protected $system_infos = "";
	/**
	 * @var UploadResult[]
	 */
	protected $screenshots = [];


	/**
	 * Support constructor
	 */
	public function __construct() {

	}


	/**
	 * Add screenshot from post file upload
	 *
	 * @param UploadResult $screenshot
	 */
	public function addScreenshot(UploadResult $screenshot)/*: void*/ {
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
	public function getPageReference(): string {
		return $this->page_reference;
	}


	/**
	 * @param string $page_reference
	 */
	public function setPageReference(string $page_reference)/*: void*/ {
		$this->page_reference = $page_reference;
	}


	/**
	 * @return Project
	 */
	public function getProject(): Project {
		return $this->project;
	}


	/**
	 * @param Project $project
	 */
	public function setProject(Project $project)/*: void*/ {
		$this->project = $project;
	}


	/**
	 * @return string
	 */
	public function getIssueType(): string {
		return $this->issue_type;
	}


	/**
	 * @param string $issue_type
	 */
	public function setIssueType(string $issue_type)/*: void*/ {
		$this->issue_type = $issue_type;
	}


	/**
	 * @return string
	 */
	public function getFixVersion(): string {
		return $this->fix_version;
	}


	/**
	 * @param string $fix_version
	 */
	public function setFixVersion(string $fix_version)/*: void*/ {
		$this->fix_version = $fix_version;
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
	 * @return UploadResult[]
	 */
	public function getScreenshots(): array {
		return $this->screenshots;
	}


	/**
	 * @param UploadResult[] $screenshots
	 */
	public function setScreenshots(array $screenshots)/*: void*/ {
		$this->screenshots = $screenshots;
	}
}
