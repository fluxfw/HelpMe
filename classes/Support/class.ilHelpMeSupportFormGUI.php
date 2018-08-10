<?php

use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;

/**
 * HelpMe Support Form GUI
 */
class ilHelpMeSupportFormGUI extends ilPropertyFormGUI {

	use srag\DIC\DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var ilHelpMeGUI
	 */
	protected $parent;


	/**
	 * @param ilHelpMeGUI $parent
	 */
	public function __construct(ilHelpMeGUI $parent) {
		parent::__construct();

		$this->parent = $parent;

		$this->setForm();
	}


	/**
	 *
	 */
	protected function setForm() {
		$configPriorities = [ "" => "&lt;" . self::t("srsu_please_select") . "&gt;" ] + ilHelpMeConfigPriority::getConfigPrioritiesArray();

		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));

		$this->addCommandButton("", self::t("srsu_screenshot_current_page"), "il_help_me_page_screenshot");
		$this->addCommandButton(ilHelpMeGUI::CMD_NEW_SUPPORT, self::t("srsu_submit"), "il_help_me_submit");
		$this->addCommandButton("", self::t("srsu_cancel"), "il_help_me_cancel");

		$this->setId("il_help_me_form");
		$this->setShowTopButtons(false);

		$title = new ilTextInputGUI(self::t("srsu_title"), "srsu_title");
		$title->setRequired(true);
		$this->addItem($title);

		$name = new ilNonEditableValueGUI(self::t("srsu_name"));
		$name->setValue(self::dic()->user()->getFullname());
		$this->addItem($name);

		$login = new ilNonEditableValueGUI(self::t("srsu_login"));
		$login->setValue(self::dic()->user()->getLogin());
		$this->addItem($login);

		$email = new ilEMailInputGUI(self::t("srsu_email_address"), "srsu_email");
		$email->setRequired(true);
		$email->setValue(self::dic()->user()->getEmail());
		$this->addItem($email);

		$phone = new ilTextInputGUI(self::t("srsu_phone"), "srsu_phone");
		$phone->setRequired(true);
		$this->addItem($phone);

		$priority = new ilSelectInputGUI(self::t("srsu_priority"), "srsu_priority");
		$priority->setRequired(true);
		$priority->setOptions($configPriorities);
		$this->addItem($priority);

		$description = new ilTextAreaInputGUI(self::t("srsu_description"), "srsu_description");
		$description->setRequired(true);
		$this->addItem($description);

		$reproduce_steps = new ilTextAreaInputGUI(self::t("srsu_reproduce_steps"), "srsu_reproduce_steps");
		$reproduce_steps->setRequired(false);
		$this->addItem($reproduce_steps);

		$system_infos = new ilNonEditableValueGUI(self::t("srsu_system_infos"));
		$system_infos->setValue($this->getBrowserInfos());
		$this->addItem($system_infos);

		$screenshot = new ilFileInputGUI(self::t("srsu_screenshot"), "srsu_screenshot");
		$screenshot->setRequired(false);
		$screenshot->setSuffixes([ "jpg", "png" ]);
		$this->addItem($screenshot);
	}


	/**
	 * @return ilHelpMeSupport
	 */
	public function getSupport() {
		$configPriorities = ilHelpMeConfigPriority::getConfigPriorities();

		$support = new ilHelpMeSupport();

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
	protected function getBrowserInfos() {
		$browser = new Browser();
		$os = new Os();

		$infos = $browser->getName() . (($browser->getVersion() !== Browser::UNKNOWN) ? " " . $browser->getVersion() : "") . " / " . $os->getName()
			. (($os->getVersion() !== Os::UNKNOWN) ? " " . $os->getVersion() : "");

		return $infos;
	}
}
