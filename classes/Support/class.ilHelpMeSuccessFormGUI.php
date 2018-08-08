<?php

/**
 * HelpMe Success Form GUI
 *
 * @property ilHelpMePlugin $pl
 */
class ilHelpMeSuccessFormGUI extends ilPropertyFormGUI {

	use srag\DIC\DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var ilHelpMeGUI
	 */
	protected $parent;


	/**
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
	protected function setForm() {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));

		$this->addCommandButton("", self::t("srsu_close"), "il_help_me_cancel");

		$this->setId("il_help_me_form");
		$this->setShowTopButtons(false);
	}
}
