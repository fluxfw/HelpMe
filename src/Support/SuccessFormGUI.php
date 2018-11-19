<?php

namespace srag\Plugins\HelpMe\Support;

use HelpMeSupportGUI;
use ilHelpMePlugin;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class SuccessFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SuccessFormGUI extends PropertyFormGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key)/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initAction()/*: void*/ {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));
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
	protected function initId()/*: void*/ {
		$this->setId("helpme_form");
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {

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
