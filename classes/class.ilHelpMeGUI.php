<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\HelpMeConfig;
use srag\Plugins\HelpMe\Config\HelpMeConfigRole;
use srag\Plugins\HelpMe\Recipient\HelpMeRecipient;
use srag\Plugins\HelpMe\Support\HelpMeSuccessFormGUI;
use srag\Plugins\HelpMe\Support\HelpMeSupportFormGUI;

/**
 * Class ilHelpMeGUI
 *
 * @ilCtrl_isCalledBy ilHelpMeGUI: ilUIPluginRouterGUI
 */
class ilHelpMeGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CMD_ADD_SUPPORT = "addSupport";
	const CMD_NEW_SUPPORT = "newSupport";


	/**
	 * ilHelpMeGUI constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand() {
		if (!HelpMeConfigRole::currentUserHasRole()) {
			die();
		}

		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch (strtolower($next_class)) {
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
	 * @return HelpMeSupportFormGUI
	 */
	protected function getSupportForm(): HelpMeSupportFormGUI {
		$form = new HelpMeSupportFormGUI($this);

		return $form;
	}


	/**
	 * @return HelpMeSuccessFormGUI
	 */
	protected function getSuccessForm(): HelpMeSuccessFormGUI {
		$form = new HelpMeSuccessFormGUI($this);

		return $form;
	}


	/**
	 * @param string|null       $message
	 * @param ilPropertyFormGUI $form
	 */
	protected function show($message, ilPropertyFormGUI $form) {
		$tpl = self::template("il_help_me_modal.html");

		$tpl->setCurrentBlock("il_help_me_info");
		$tpl->setVariable("INFO", HelpMeConfig::getInfo());

		if ($message !== NULL) {
			$tpl->setCurrentBlock("il_help_me_message");
			$tpl->setVariable("MESSAGE", $message);
		}

		$tpl->setCurrentBlock("il_help_me_form");
		$tpl->setVariable("FORM", $form->getHTML());

		self::output($tpl);
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

		$recipient = HelpMeRecipient::getRecipient(HelpMeConfig::getRecipient(), $support);
		if ($recipient->sendSupportToRecipient()) {
			$message = self::dic()->tpl()->getMessageHTML(self::translate("srsu_sent_success"), "success");

			$form = $this->getSuccessForm();
		} else {
			$message = self::dic()->tpl()->getMessageHTML(self::translate("srsu_sent_failure"), "failure");
		}

		$this->show($message, $form);
	}
}
