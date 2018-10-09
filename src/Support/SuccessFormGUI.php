<?php

namespace srag\Plugins\HelpMe\Support;

use HelpMeGUI;
use ilHelpMePlugin;
use ilPropertyFormGUI;
use srag\DIC\DICTrait;

/**
 * Class SuccessFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SuccessFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var HelpMeGUI
	 */
	protected $parent;


	/**
	 * SuccessFormGUI constructor
	 *
	 * @param HelpMeGUI $parent
	 */
	public function __construct(HelpMeGUI $parent) {
		parent::__construct();

		$this->parent = $parent;

		$this->initForm();
	}


	/**
	 *
	 */
	protected function initForm()/*: void*/ {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));

		$this->addCommandButton("", self::plugin()->translate("srsu_close"), "il_help_me_cancel");

		$this->setId("il_help_me_form");
		$this->setShowTopButtons(false);
	}
}
