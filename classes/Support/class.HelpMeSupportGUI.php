<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Recipient\Recipient;
use srag\Plugins\HelpMe\Support\SuccessFormGUI;
use srag\Plugins\HelpMe\Support\SupportFormGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class HelpMeSupportGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy HelpMeSupportGUI: ilUIPluginRouterGUI
 */
class HelpMeSupportGUI {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CMD_ADD_SUPPORT = "addSupport";
	const CMD_NEW_SUPPORT = "newSupport";
	const LANG_MODULE_SUPPORT = "support";


	/**
	 * HelpMeSupportGUI constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		if (!self::access()->currentUserHasRole()) {
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
		$tpl = self::plugin()->template("helpme_modal.html");

		$tpl->setCurrentBlock("helpme_info");
		$tpl->setVariable("INFO", Config::getField(Config::KEY_INFO));

		if ($message !== NULL) {
			$tpl->setCurrentBlock("helpme_message");
			$tpl->setVariable("MESSAGE", $message);
		}

		$tpl->setCurrentBlock("helpme_form");
		$tpl->setVariable("FORM", self::output()->getHTML($form));

		self::output()->output($tpl);
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

		if (!$form->storeForm()) {
			$this->show($message, $form);

			return;
		}

		$support = $form->getSupport();

		$recipient = Recipient::getRecipient(Config::getField(Config::KEY_RECIPIENT), $support);
		if ($recipient->sendSupportToRecipient()) {
			$message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()->translate("sent_success", self::LANG_MODULE_SUPPORT), "success");

			$form = $this->getSuccessForm();
		} else {
			$message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()->translate("sent_failure", self::LANG_MODULE_SUPPORT), "failure");
		}

		$this->show($message, $form);
	}
}
