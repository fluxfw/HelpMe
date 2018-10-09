<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Config\ConfigRole;
use srag\Plugins\HelpMe\Recipient\Recipient;
use srag\Plugins\HelpMe\Support\SuccessFormGUI;
use srag\Plugins\HelpMe\Support\SupportFormGUI;

/**
 * Class HelpMeGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy HelpMeGUI: ilUIPluginRouterGUI
 */
class HelpMeGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CMD_ADD_SUPPORT = "addSupport";
	const CMD_NEW_SUPPORT = "newSupport";


	/**
	 * HelpMeGUI constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		if (!ConfigRole::currentUserHasRole()) {
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
	 * @return SupportFormGUI
	 */
	protected function getSupportForm(): SupportFormGUI {
		$form = new SupportFormGUI($this);

		return $form;
	}


	/**
	 * @return SuccessFormGUI
	 */
	protected function getSuccessForm(): SuccessFormGUI {
		$form = new SuccessFormGUI($this);

		return $form;
	}


	/**
	 * @param string|null       $message
	 * @param ilPropertyFormGUI $form
	 */
	protected function show(/*?string*/
		$message, ilPropertyFormGUI $form)/*: void*/ {
		$tpl = self::plugin()->template("il_help_me_modal.html");

		$tpl->setCurrentBlock("il_help_me_info");
		$tpl->setVariable("INFO", Config::getInfo());

		if ($message !== NULL) {
			$tpl->setCurrentBlock("il_help_me_message");
			$tpl->setVariable("MESSAGE", $message);
		}

		$tpl->setCurrentBlock("il_help_me_form");
		$tpl->setVariable("FORM", $form->getHTML());

		self::plugin()->output($tpl);
	}


	/**
	 *
	 */
	protected function addSupport()/*: void*/ {
		$message = NULL;

		$form = $this->getSupportForm();

		$this->show($message, $form);
	}


	/**
	 *
	 */
	protected function newSupport()/*: void*/ {
		$message = NULL;

		$form = $this->getSupportForm();
		$form->setValuesByPost();

		if (!$form->checkInput()) {
			$this->show($message, $form);

			return;
		}

		$support = $form->getSupport();

		$recipient = Recipient::getRecipient(Config::getRecipient(), $support);
		if ($recipient->sendSupportToRecipient()) {
			$message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()->translate("srsu_sent_success"), "success");

			$form = $this->getSuccessForm();
		} else {
			$message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()->translate("srsu_sent_failure"), "failure");
		}

		$this->show($message, $form);
	}
}
