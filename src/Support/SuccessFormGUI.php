<?php

namespace srag\Plugins\HelpMe\Support;

use HelpMeSupportGUI;
use ilHelpMePlugin;
use ilPropertyFormGUI;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class SuccessFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SuccessFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var HelpMeSupportGUI
	 */
	protected $parent;


	/**
	 * SuccessFormGUI constructor
	 *
	 * @param HelpMeSupportGUI $parent
	 */
	public function __construct(HelpMeSupportGUI $parent) {
		parent::__construct();

		$this->parent = $parent;

		$this->initForm();
	}


	/**
	 *
	 */
	protected function initForm()/*: void*/ {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));

		$this->addCommandButton("", self::plugin()->translate("close", HelpMeSupportGUI::LANG_MODULE_SUPPORT), "helpme_cancel");

		$this->setId("helpme_form");
		$this->setShowTopButtons(false);
	}
}
