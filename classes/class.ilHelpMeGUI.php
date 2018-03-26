<?php
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * HelpMe GUI
 *
 * @ilCtrl_isCalledBy ilHelpMeGUI: ilUIPluginRouterGUI
 */
class ilHelpMeGUI {

	const CMD_ADD_SUPPORT = "addSupport";
	const CMD_NEW_SUPPORT = "newSupport";
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
		global $DIC;

		$this->ctrl = $DIC->ctrl();
		$this->pl = ilHelpMePlugin::getInstance();
		$this->tpl = $DIC->ui()->mainTemplate();
		$this->usr = $DIC->user();
	}


	/**
	 *
	 */
	function executeCommand() {
		if (!ilHelpMeConfigRole::currentUserHasRole()) {
			die();
		}

		$next_class = $this->ctrl->getNextClass($this);

		switch ($next_class) {
			default:
				$cmd = $this->ctrl->getCmd();

				switch ($cmd) {
					case self::CMD_ADD_SUPPORT:
					case self::CMD_NEW_SUPPORT:
						$this->{$cmd}();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 * @return ilHelpMeSupportFormGUI
	 */
	protected function getSupportForm() {
		$form = new ilHelpMeSupportFormGUI($this);

		$form->setForm();

		return $form;
	}


	/**
	 * @return ilHelpMeSuccessFormGUI
	 */
	protected function getSuccessForm() {
		$form = new ilHelpMeSuccessFormGUI($this);

		$form->setForm();

		return $form;
	}


	/**
	 * @param string|null       $message
	 * @param ilPropertyFormGUI $form
	 */
	protected function show($message = NULL, ilPropertyFormGUI $form) {
		$config = ilHelpMeConfig::getConfig();

		$tpl = $this->pl->getTemplate("il_help_me_modal.html");

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

		$support = $form->getSupport();
		$config = ilHelpMeConfig::getConfig();

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
