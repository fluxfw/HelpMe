<?php
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * HelpMe GUI
 *
 * @ilCtrl_isCalledBy ilHelpMeGUI: ilUIPluginRouterGUI
 *
 * @property ilHelpMePlugin $pl
 */
class ilHelpMeGUI {

	use srag\DIC\DIC;
	const CMD_ADD_SUPPORT = "addSupport";
	const CMD_NEW_SUPPORT = "newSupport";


	/**
	 *
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand() {
		if (!ilHelpMeConfigRole::currentUserHasRole()) {
			die();
		}

		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch ($next_class) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

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

		return $form;
	}


	/**
	 * @return ilHelpMeSuccessFormGUI
	 */
	protected function getSuccessForm() {
		$form = new ilHelpMeSuccessFormGUI($this);

		return $form;
	}


	/**
	 * @param string|null       $message
	 * @param ilPropertyFormGUI $form
	 */
	protected function show($message, ilPropertyFormGUI $form) {
		$config = ilHelpMeConfig::getConfig();

		$tpl = $this->getTemplate("il_help_me_modal.html");

		$tpl->setCurrentBlock("il_help_me_info");
		$tpl->setVariable("INFO", $config->getInfo());

		if ($message !== NULL) {
			$tpl->setCurrentBlock("il_help_me_message");
			$tpl->setVariable("MESSAGE", $message);
		}

		$tpl->setCurrentBlock("il_help_me_form");
		$tpl->setVariable("FORM", $form->getHTML());

		$html = $tpl->get();

		if (self::dic()->ctrl()->isAsynch()) {
			echo $html;

			exit();
		} else {
			self::dic()->tpl()->setContent($html);
		}
	}


	/**
	 *
	 */
	protected function addSupport() {
		$message = NULL;

		$form = $this->getSupportForm();

		$this->show($message, $form);
	}


	/**
	 *
	 */
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
			$message = self::dic()->tpl()->getMessageHTML($this->getTemplate("srsu_sent_success"), "success");

			$form = $this->getSuccessForm();
		} else {
			$message = self::dic()->tpl()->getMessageHTML($this->txt("srsu_sent_failure"), "failure");
		}

		$this->show($message, $form);
	}
}
