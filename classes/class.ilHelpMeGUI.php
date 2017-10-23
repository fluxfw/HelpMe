<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/class.ilHelpMePlugin.php";
require_once "Services/Form/classes/class.ilPropertyFormGUI.php";
require_once "Services/Form/classes/class.ilTextInputGUI.php";
require_once "Services/Form/classes/class.ilEMailInputGUI.php";
require_once "Services/Form/classes/class.ilSelectInputGUI.php";
require_once "Services/Form/classes/class.ilTextAreaInputGUI.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeSupport.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipient.php";
require_once "Services/Form/classes/class.ilFileInputGUI.php";

/**
 * HelpMe GUI
 *
 * @ilCtrl_isCalledBy ilHelpMeGUI: ilUIPluginRouterGUI
 */
class ilHelpMeGUI {

	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilHelpMePlugin
	 */
	protected $pl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilObjUser
	 */
	protected $usr;


	function __construct() {
		/**
		 * @var ilCtrl     $ilCtrl
		 * @var ilObjUser  $ilUser
		 * @var ilTemplate $tpl
		 */

		global $ilCtrl, $ilUser, $tpl;

		$this->ctrl = $ilCtrl;
		$this->pl = ilHelpMePlugin::getInstance();
		$this->tpl = $tpl;
		$this->usr = $ilUser;
	}


	/**
	 *
	 */
	function executeCommand() {
		if (!$this->pl->currentUserHasRole()) {
			die();
		}

		$cmd = $this->ctrl->getCmd();

		switch ($cmd) {
			case "addSupport":
			case "newSupport":
				$this->{$cmd}();
				break;

			default:
				break;
		}
	}


	/**
	 * @return ilPropertyFormGUI
	 */
	protected function getSupportForm() {
		$configPriorities = $this->pl->getConfigPrioritiesArray();

		$form = new ilPropertyFormGUI();

		$form->setFormAction($this->ctrl->getFormAction($this, "", "", true));

		$form->addCommandButton("", $this->txt("srsu_screenshot_current_page"), "il_help_me_page_screenshot");
		$form->addCommandButton("newSupport", $this->txt("srsu_submit"), "il_help_me_submit");
		$form->addCommandButton("", $this->txt("srsu_cancel"), "il_help_me_cancel");

		$form->setId("il_help_me_form");
		$form->setShowTopButtons(false);

		$title = new ilTextInputGUI($this->txt("srsu_title"), "srsu_title");
		$title->setRequired(true);
		$form->addItem($title);

		$email = new ilEMailInputGUI($this->txt("srsu_email_address"), "srsu_email");
		$email->setRequired(true);
		$email->setValue($this->usr->getEmail());
		$form->addItem($email);

		$phone = new ilTextInputGUI($this->txt("srsu_phone"), "srsu_phone");
		$phone->setRequired(true);
		$form->addItem($phone);

		$priority = new ilSelectInputGUI($this->txt("srsu_priority"), "srsu_priority");
		$priority->setRequired(true);
		$priority->setOptions($configPriorities);
		$form->addItem($priority);

		$description = new ilTextAreaInputGUI($this->txt("srsu_description"), "srsu_description");
		$description->setRequired(true);
		$form->addItem($description);

		$reproduce_steps = new ilTextAreaInputGUI($this->txt("srsu_reproduce_steps"), "srsu_reproduce_steps");
		$reproduce_steps->setRequired(true);
		$form->addItem($reproduce_steps);

		$screenshot = new ilFileInputGUI($this->txt("srsu_screenshot"), "srsu_screenshot");
		$screenshot->setRequired(false);
		$screenshot->setSuffixes([ "jpg", "png" ]);
		$form->addItem($screenshot);

		return $form;
	}


	/**
	 * @return ilPropertyFormGUI
	 */
	protected function getSuccessForm() {
		$form = new ilPropertyFormGUI();

		$form->setFormAction($this->ctrl->getFormAction($this, "", "", true));

		$form->addCommandButton("", $this->txt("srsu_close"), "il_help_me_cancel");

		$form->setId("il_help_me_form");
		$form->setShowTopButtons(false);

		return $form;
	}


	/**
	 * @param string|null       $message
	 * @param ilPropertyFormGUI $form
	 */
	protected function show($message, $form) {
		$config = $this->pl->getConfig();

		$tpl = $this->pl->getTemplate("il_help_me_modal.html", true, true);

		$tpl->setCurrentBlock("il_help_me_info");
		$tpl->setVariable("INFO", $config->getInfo());

		if ($message !== NULL) {
			$tpl->setCurrentBlock("il_help_me_message");
			$tpl->setVariable("MESSAGE", $message);
		}

		$tpl->setCurrentBlock("il_help_me_form");
		$tpl->setVariable("FORM", $form->getHTML());

		$html = $tpl->get();

		if ($this->ctrl->isAsynch()) {
			echo $html;

			exit();
		} else {
			$this->tpl->setContent($html);
		}
	}


	protected function addSupport() {
		$message = NULL;

		$form = $this->getSupportForm();

		$this->show($message, $form);
	}


	protected function newSupport() {
		$message = NULL;

		$form = $this->getSupportForm();
		$form->setValuesByPost();

		if (!$form->checkInput()) {
			$this->show($message, $form);

			return;
		}

		$config = $this->pl->getConfig();
		$configPriorities = $this->pl->getConfigPriorities();

		$support = new ilHelpMeSupport();

		$title = $form->getInput("srsu_title");
		$support->setTitle($title);

		$time = time();
		$support->setTime($time);

		$email = $form->getInput("srsu_email");
		$support->setEmail($email);

		$phone = $form->getInput("srsu_phone");
		$support->setPhone($phone);

		$priority_id = $form->getInput("srsu_priority");
		foreach ($configPriorities as $priority) {
			if ($priority->getId() === $priority_id) {
				$support->setPriority($priority);
				break;
			}
		}

		$description = $form->getInput("srsu_description");
		$support->setDescription($description);

		$reproduce_steps = $form->getInput("srsu_reproduce_steps");
		$support->setReproduceSteps($reproduce_steps);

		$screenshot = $form->getInput("srsu_screenshot");
		if ($screenshot["tmp_name"] != "") {
			$support->addScreenshot($screenshot);
		}

		$recipient = ilHelpMeRecipient::getRecipient($config->getRecipient(), $support, $config);
		if ($recipient->sendSupportToRecipient()) {
			$message = $this->tpl->getMessageHTML($this->txt("srsu_sent_success"), "success");

			$form = $this->getSuccessForm();
		} else {
			$message = $this->tpl->getMessageHTML($this->txt("srsu_sent_failure"), "failure");
		}

		$this->show($message, $form);
	}


	/**
	 * @param string $a_var
	 *
	 * @return string
	 */
	protected function txt($a_var) {
		return $this->pl->txt($a_var);
	}
}
