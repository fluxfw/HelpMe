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
	const CMD_GET_ISSUE_TYPES_OF_PROJECT = "getIssueTypesOfProject";
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
			default:
				$cmd = self::dic()->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_ADD_SUPPORT:
					case self::CMD_GET_ISSUE_TYPES_OF_PROJECT:
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
	protected function getIssueTypesOfProject()/*: void*/ {
		$project_url_key = filter_input(INPUT_GET, "project_url_key");

		$project = self::projects()->getProjectByUrlKey($project_url_key);

		$form = $this->getSupportForm();

		$issue_type_select = $form->extractIssueTypeSelector();

		if ($project !== null) {
			$issue_type_select->setOptions([
					"" => "&lt;" . $form->txt("please_select") . "&gt;"
				] + self::projects()->getIssueTypesOptions($project));
			$issue_type_select->setDisabled(false);
		}

		self::output()->output($issue_type_select);
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
