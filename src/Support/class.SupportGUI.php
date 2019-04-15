<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ilLogLevel;
use ilPropertyFormGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Recipient\Recipient;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use Throwable;

/**
 * Class SupportGUI
 *
 * @package           srag\Plugins\HelpMe\Support
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Support\SupportGUI: ilUIPluginRouterGUI
 */
class SupportGUI {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CMD_ADD_SUPPORT = "addSupport";
	const CMD_NEW_SUPPORT = "newSupport";
	const LANG_MODULE_SUPPORT = "support";


	/**
	 * SupportGUI constructor
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
			case strtolower(ProjectSelectInputGUI::class):
				self::dic()->ctrl()->forwardCommand($this->getSupportForm()->extractProjectSelector());
				break;

			case strtolower(IssueTypeSelectInputGUI::class):
				self::dic()->ctrl()->forwardCommand($this->getSupportForm()->extractIssueTypeSelector());
				break;

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

		if ($message !== null) {
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
		$message = null;

		$form = $this->getSupportForm();

		$this->show($message, $form);
	}


	/**
	 *
	 */
	protected function newSupport()/*: void*/ {
		$message = null;

		$form = $this->getSupportForm();

		if (!$form->storeForm()) {
			$this->show($message, $form);

			return;
		}

		$support = $form->getSupport();

		try {
			$recipient = Recipient::getRecipient(Config::getField(Config::KEY_RECIPIENT), $support);

			$recipient->sendSupportToRecipient();

			if (self::version()->is54()) {
				$message = self::output()->getHTML(self::dic()->ui()->factory()->messageBox()->success(self::plugin()
					->translate("sent_success", self::LANG_MODULE_SUPPORT)));
			} else {
				$message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()
					->translate("sent_success", self::LANG_MODULE_SUPPORT), "success");
			}

			$form = $this->getSuccessForm();
		} catch (Throwable $ex) {
			self::dic()->logger()->root()->log($ex->__toString(), ilLogLevel::ERROR);

			if (self::version()->is54()) {
				$message = self::output()->getHTML(self::dic()->ui()->factory()->messageBox()->failure(self::plugin()
					->translate("sent_failure", self::LANG_MODULE_SUPPORT)));
			} else {
				$message = self::dic()->mainTemplate()->getMessageHTML(self::plugin()
					->translate("sent_failure", self::LANG_MODULE_SUPPORT), "failure");
			}
		}

		$this->show($message, $form);
	}
}
