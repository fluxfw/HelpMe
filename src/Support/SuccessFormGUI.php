<?php

namespace srag\Plugins\HelpMe\Support;

use HelpMeSupportGUI;

/**
 * Class SuccessFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SuccessFormGUI extends SupportFormGUI {

	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key)/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$this->addCommandButton("", self::plugin()->translate("close", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "helpme_cancel");

		$this->setShowTopButtons(false);
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function setValue(/*string*/
		$key, $value)/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	public function updateForm()/*: void*/ {

	}
}
