<?php

namespace srag\Plugins\HelpMe\Support;

use ilEMailInputGUI;
use ilHelpMePlugin;
use ilHelpMeUIHookGUI;
use ilNonEditableValueGUI;
use ilSelectInputGUI;
use ilSession;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\ScreenshotsInputGUI\ScreenshotsInputGUI;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class SupportFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SupportFormGUI extends PropertyFormGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const LANG_MODULE = SupportGUI::LANG_MODULE_SUPPORT;
	/**
	 * @var Support|null
	 */
	protected $support = null;
	/**
	 * @var Project|null
	 */
	protected $project = null;


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/ $key) {
		switch ($key) {
			case "page_reference":
				return self::supports()->getRefLink();

			case "project":
				if ($this->project !== null) {
					return $this->project->getProjectUrlKey();
				}
				break;

			case "name":
				if (self::ilias()->users()->getUserId() !== intval(ANONYMOUS_USER_ID)) {
					return self::dic()->user()->getFullname();
				}
				break;

			case "login":
				return self::dic()->user()->getLogin();

			case "email":
				if (self::ilias()->users()->getUserId() !== intval(ANONYMOUS_USER_ID)) {
					return self::dic()->user()->getEmail();
				}
				break;

			case "system_infos":
				return self::supports()->getBrowserInfos();

			default:
				break;
		}

		return null;
	}


	/**
	 * @inheritdoc
	 */
	protected final function initAction()/*: void*/ {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$this->addCommandButton(SupportGUI::CMD_NEW_SUPPORT, $this->txt("submit"), "helpme_submit");

		$this->addCommandButton("", $this->txt("cancel"), "helpme_cancel");

		$this->setShowTopButtons(false);
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		// Preselect project (Support link)
		$project_url_key = ilSession::get(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);
		if (!empty($project_url_key)) {
			ilSession::clear(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);

			$this->project = self::projects()->getProjectByUrlKey($project_url_key);
		}

		$this->fields = (self::supports()->getRefId() !== null ? [
				"page_reference" => [
					self::PROPERTY_CLASS => ilNonEditableValueGUI::class
				],
			] : []) + [
				"project" => [
					self::PROPERTY_CLASS => ProjectSelectInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_OPTIONS => [
							"" => "&lt;" . $this->txt("please_select") . "&gt;"
						] + self::projects()->getProjectsOptions()
				],
				"issue_type" => [
					self::PROPERTY_CLASS => IssueTypeSelectInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_OPTIONS => [],
					self::PROPERTY_DISABLED => true
				],
				"title" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"name" => [
					self::PROPERTY_CLASS => (self::ilias()->users()->getUserId()
					=== intval(ANONYMOUS_USER_ID) ? ilTextInputGUI::class : ilNonEditableValueGUI::class),
					self::PROPERTY_REQUIRED => true
				],
				"login" => [
					self::PROPERTY_CLASS => ilNonEditableValueGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"email" => [
					self::PROPERTY_CLASS => ilEMailInputGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"phone" => [
					self::PROPERTY_CLASS => ilTextInputGUI::class,
					self::PROPERTY_REQUIRED => false
				],
				"priority" => [
					self::PROPERTY_CLASS => ilSelectInputGUI::class,
					self::PROPERTY_REQUIRED => true,
					self::PROPERTY_OPTIONS => [
							"" => "&lt;" . $this->txt("please_select") . "&gt;"
						] + Config::getField(Config::KEY_PRIORITIES)
				],
				"description" => [
					self::PROPERTY_CLASS => ilTextAreaInputGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"reproduce_steps" => [
					self::PROPERTY_CLASS => ilTextAreaInputGUI::class,
					self::PROPERTY_REQUIRED => false
				],
				"system_infos" => [
					self::PROPERTY_CLASS => ilNonEditableValueGUI::class,
					self::PROPERTY_REQUIRED => true
				],
				"screenshots" => [
					self::PROPERTY_CLASS => ScreenshotsInputGUI::class,
					self::PROPERTY_REQUIRED => false,
					"withPlugin" => self::plugin()
				]
			];
	}


	/**
	 * @inheritdoc
	 */
	protected final function initId()/*: void*/ {
		$this->setId("helpme_form");
	}


	/**
	 * @inheritdoc
	 */
	protected final function initTitle()/*: void*/ {
	}


	/**
	 * @inheritdoc
	 */
	public function storeForm(): bool {
		$this->support = self::supports()->factory()->newInstance();

		$time = time();
		$this->support->setTime($time);

		return parent::storeForm();
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/ $key, $value)/*: void*/ {
		switch ($key) {
			case "page_reference":
				$this->support->setPageReference(self::supports()->getRefLink());
				break;

			case "project":
				$this->support->setProject($this->project);
				break;

			case "issue_type":
				$this->support->setIssueType($value);
				$this->support->setFixVersion(self::projects()->getFixVersionForIssueType($this->project, $this->support->getIssueType()));
				break;

			case "title":
				$this->support->setTitle($value);
				break;

			case "name":
				if (self::ilias()->users()->getUserId() === intval(ANONYMOUS_USER_ID)) {
					$this->support->setName($value);
				} else {
					$this->support->setName(self::dic()->user()->getFullname());
				}
				break;

			case "login":
				$this->support->setLogin(self::dic()->user()->getLogin());
				break;

			case "email":
				$this->support->setEmail($value);
				break;

			case "phone":
				$this->support->setPhone($value);
				break;

			case "priority":
				$configPriorities = Config::getField(Config::KEY_PRIORITIES);

				$priority_id = intval($value);

				foreach ($configPriorities as $id => $priority) {
					if ($id === $priority_id) {
						$this->support->setPriority($priority);
						break;
					}
				}
				break;

			case "description":
				$this->support->setDescription($value);
				break;

			case "reproduce_steps":
				$this->support->setReproduceSteps($value);
				break;

			case "system_infos":
				$this->support->setSystemInfos(self::supports()->getBrowserInfos());
				break;

			case "screenshots":
				foreach ($value as $screenshot) {
					$this->support->addScreenshot($screenshot);
				}
				break;

			default:
				break;
		}
	}


	/**
	 * @return Support
	 */
	public function getSupport(): Support {
		return $this->support;
	}


	/**
	 * @return Project|null
	 */
	public function getProject()/*: ?Project*/ {
		return $this->project;
	}


	/**
	 * @param Project|null $project
	 */
	public function setProject(/*?*/ Project $project = null)/*: void*/ {
		$this->project = $project;
	}


	/**
	 * @return ProjectSelectInputGUI
	 */
	public function extractProjectSelector(): ProjectSelectInputGUI {
		return $this->getItemByPostVar("project");
	}


	/**
	 * @return IssueTypeSelectInputGUI
	 */
	public function extractIssueTypeSelector(): IssueTypeSelectInputGUI {
		return $this->getItemByPostVar("issue_type");
	}
}
