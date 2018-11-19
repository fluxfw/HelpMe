<?php

namespace srag\Plugins\HelpMe\Support;

use HelpMeSupportGUI;
use ilEMailInputGUI;
use ilHelpMePlugin;
use ilHelpMeUIHookGUI;
use ilNonEditableValueGUI;
use ilSelectInputGUI;
use ilSession;
use ilTextInputGUI;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\ScreenshotsInputGUI\ScreenshotsInputGUI;
use srag\Plugins\HelpMe\Config\Config;
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
	const LANG_MODULE = HelpMeSupportGUI::LANG_MODULE_SUPPORT;
	/**
	 * @var Support|null
	 */
	protected $support = NULL;


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		switch ($key) {
			case "project":
				$project_key = ilSession::get(ilHelpMeUIHookGUI::SESSION_PROJECT_KEY);

				if ($project_key !== NULL) {
					ilSession::clear(ilHelpMeUIHookGUI::SESSION_PROJECT_KEY);

					return $project_key;
				}
				break;

			case "name":
				return self::dic()->user()->getFullname();

			case "login":
				return self::dic()->user()->getLogin();

			case "email":
				return self::dic()->user()->getEmail();

			case "system_infos":
				return $this->getBrowserInfos();

			default:
				break;
		}

		return NULL;
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
		$this->addCommandButton(HelpMeSupportGUI::CMD_NEW_SUPPORT, self::plugin()->translate("submit", self::LANG_MODULE), "helpme_submit");

		$this->addCommandButton("", self::plugin()->translate("cancel", self::LANG_MODULE), "helpme_cancel");

		$this->setShowTopButtons(false);
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			"project" => [
				self::PROPERTY_CLASS => ilSelectInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_OPTIONS => [
						"" => "&lt;" . self::plugin()->translate("please_select", self::LANG_MODULE) . "&gt;"
					] + Config::getField(Config::KEY_PROJECTS)
			],
			"title" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"name" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"login" => [
				self::PROPERTY_CLASS => ilNonEditableValueGUI::class
			],
			"email" => [
				self::PROPERTY_CLASS => ilEMailInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"phone" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"priority" => [
				self::PROPERTY_CLASS => ilSelectInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_OPTIONS => [
						"" => "&lt;" . self::plugin()->translate("please_select", self::LANG_MODULE) . "&gt;"
					] + Config::getField(Config::KEY_PRIORITIES)
			],
			"description" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"reproduce_steps" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => false
			],
			"system_infos" => [
				self::PROPERTY_CLASS => ilNonEditableValueGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"screenshots" => [
				self::PROPERTY_CLASS => ScreenshotsInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				"setPlugin" => self::plugin()
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
	protected function setValue(/*string*/
		$key, $value)/*: void*/ {
	}


	/**
	 * @inheritdoc
	 */
	public function updateForm()/*: void*/ {
		$configPriorities = Config::getField(Config::KEY_PRIORITIES);

		$this->support = new Support();

		$time = time();
		$this->support->setTime($time);

		$project = $this->getInput("project");
		$this->support->setProject($project);

		$title = $this->getInput("title");
		$this->support->setTitle($title);

		$name = self::dic()->user()->getFullname();
		$this->support->setName($name);

		$login = self::dic()->user()->getLogin();
		$this->support->setLogin($login);

		$email = $this->getInput("email");
		$this->support->setEmail($email);

		$phone = $this->getInput("phone");
		$this->support->setPhone($phone);

		$priority_id = (int)$this->getInput("priority");
		foreach ($configPriorities as $id => $priority) {
			if ($id === $priority_id) {
				$this->support->setPriority($priority);
				break;
			}
		}

		$description = $this->getInput("description");
		$this->support->setDescription($description);

		$reproduce_steps = $this->getInput("reproduce_steps");
		$this->support->setReproduceSteps($reproduce_steps);

		$system_infos = $this->getBrowserInfos();
		$this->support->setSystemInfos($system_infos);

		$screenshots = $this->getItemByPostVar("screenshots")->getValue();
		foreach ($screenshots as $screenshot) {
			$this->support->addScreenshot($screenshot);
		}
	}


	/**
	 * Get browser infos
	 *
	 * @return string "Browser Version / System Version"
	 */
	protected function getBrowserInfos(): string {
		$browser = new Browser();
		$os = new Os();

		$infos = $browser->getName() . (($browser->getVersion() !== Browser::UNKNOWN) ? " " . $browser->getVersion() : "") . " / " . $os->getName()
			. (($os->getVersion() !== Os::UNKNOWN) ? " " . $os->getVersion() : "");

		return $infos;
	}


	/**
	 * @return Support
	 */
	public function getSupport(): Support {
		return $this->support;
	}
}
