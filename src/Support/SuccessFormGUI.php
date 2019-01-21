<?php

namespace srag\Plugins\HelpMe\Support;

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
		$this->addCommandButton("", $this->txt("close"), "helpme_cancel");

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
	public function storeForm()/*: bool*/ {
		return false;
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {

	}
}
