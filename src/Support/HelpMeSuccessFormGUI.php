<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMeGUI;
use ilHelpMePlugin;
use ilPropertyFormGUI;
use srag\DIC\DICTrait;

/**
 * Class HelpMeSuccessFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag <support-custom1@studer-raimann.ch>
 */
class HelpMeSuccessFormGUI extends ilPropertyFormGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var ilHelpMeGUI
	 */
	protected $parent;


	/**
	 * HelpMeSuccessFormGUI constructor
	 *
	 * @param ilHelpMeGUI $parent
	 */
	public function __construct(ilHelpMeGUI $parent) {
		parent::__construct();

		$this->parent = $parent;

		$this->setForm();
	}


	/**
	 *
	 */
	protected function setForm()/*: void*/ {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));

		$this->addCommandButton("", self::plugin()->translate("srsu_close"), "il_help_me_cancel");

		$this->setId("il_help_me_form");
		$this->setShowTopButtons(false);
	}
}
