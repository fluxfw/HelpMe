<?php

namespace srag\Plugins\HelpMe\Support;

use HelpMeSupportGUI;
use ilEMailInputGUI;
use ilFileInputGUI;
use ilHelpMePlugin;
use ilNonEditableValueGUI;
use ilPropertyFormGUI;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\ConfigPriority;

/**
 * Class SupportFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SupportFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var HelpMeSupportGUI
	 */
	protected $parent;


	/**
	 * SupportFormGUI constructor
	 *
	 * @param HelpMeSupportGUI $parent
	 */
	public function __construct(HelpMeSupportGUI $parent) {
		parent::__construct();

		$this->parent = $parent;

		$this->initForm();
	}


	/**
	 *
	 */
	protected function initForm()/*: void*/ {
		$configPriorities = [ "" => "&lt;" . self::plugin()->translate("please_select", HelpMeSupportGUI::LANG_MODULE_SUPPORT) . "&gt;" ]
			+ ConfigPriority::getConfigPrioritiesArray();

		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));

		$this->addCommandButton("", self::plugin()
			->translate("screenshot_current_page", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "il_help_me_page_screenshot");
		$this->addCommandButton(HelpMeSupportGUI::CMD_NEW_SUPPORT, self::plugin()
			->translate("submit", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "il_help_me_submit");
		$this->addCommandButton("", self::plugin()->translate("cancel", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "il_help_me_cancel");

		$this->setId("il_help_me_form");
		$this->setShowTopButtons(false);

		$title = new ilTextInputGUI(self::plugin()->translate("title", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "srsu_title");
		$title->setRequired(true);
		$this->addItem($title);

		$name = new ilNonEditableValueGUI(self::plugin()->translate("name", HelpMeSupportGUI::LANG_MODULE_SUPPORT));
		$name->setValue(self::dic()->user()->getFullname());
		$this->addItem($name);

		$login = new ilNonEditableValueGUI(self::plugin()->translate("login", HelpMeSupportGUI::LANG_MODULE_SUPPORT));
		$login->setValue(self::dic()->user()->getLogin());
		$this->addItem($login);

		$email = new ilEMailInputGUI(self::plugin()->translate("email_address", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "srsu_email");
		$email->setRequired(true);
		$email->setValue(self::dic()->user()->getEmail());
		$this->addItem($email);

		$phone = new ilTextInputGUI(self::plugin()->translate("phone", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "srsu_phone");
		$phone->setRequired(true);
		$this->addItem($phone);

		$priority = new ilSelectInputGUI(self::plugin()->translate("priority", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "srsu_priority");
		$priority->setRequired(true);
		$priority->setOptions($configPriorities);
		$this->addItem($priority);

		$description = new ilTextAreaInputGUI(self::plugin()->translate("description", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "srsu_description");
		$description->setRequired(true);
		$this->addItem($description);

		$reproduce_steps = new ilTextAreaInputGUI(self::plugin()
			->translate("reproduce_steps", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "srsu_reproduce_steps");
		$reproduce_steps->setRequired(false);
		$this->addItem($reproduce_steps);

		$system_infos = new ilNonEditableValueGUI(self::plugin()->translate("system_infos", HelpMeSupportGUI::LANG_MODULE_SUPPORT));
		$system_infos->setValue($this->getBrowserInfos());
		$this->addItem($system_infos);

		$screenshot = new ilFileInputGUI(self::plugin()->translate("screenshot", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "srsu_screenshot");
		$screenshot->setRequired(false);
		$screenshot->setSuffixes([ "jpg", "png" ]);
		$this->addItem($screenshot);
	}


	/**
	 * @return Support
	 */
	public function getSupport(): Support {
		$configPriorities = ConfigPriority::getConfigPriorities();

		$support = new Support();

		$time = time();
		$support->setTime($time);

		$title = $this->getInput("srsu_title");
		$support->setTitle($title);

		$name = self::dic()->user()->getFullname();
		$support->setName($name);

		$login = self::dic()->user()->getLogin();
		$support->setLogin($login);

		$email = $this->getInput("srsu_email");
		$support->setEmail($email);

		$phone = $this->getInput("srsu_phone");
		$support->setPhone($phone);

		$priority_id = (int)$this->getInput("srsu_priority");
		foreach ($configPriorities as $priority) {
			if ($priority->getId() === $priority_id) {
				$support->setPriority($priority);
				break;
			}
		}

		$description = $this->getInput("srsu_description");
		$support->setDescription($description);

		$reproduce_steps = $this->getInput("srsu_reproduce_steps");
		$support->setReproduceSteps($reproduce_steps);

		$system_infos = $this->getBrowserInfos();
		$support->setSystemInfos($system_infos);

		$screenshot = $this->getInput("srsu_screenshot");
		if ($screenshot["tmp_name"] != "") {
			$support->addScreenshot($screenshot);
		}

		return $support;
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
}
