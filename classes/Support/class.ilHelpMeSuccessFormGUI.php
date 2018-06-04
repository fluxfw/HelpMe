<?php

/**
 * HelpMe Success Form GUI
 */
class ilHelpMeSuccessFormGUI extends ilPropertyFormGUI {

	use \srag\DICTrait;
	/**
	 * @var ilHelpMeGUI
	 */
	protected $parent;
	/**
	 * @var ilHelpMePlugin
	 */
	protected $pl;


	/**
	 * @param ilHelpMeGUI $parent
	 */
	public function __construct(ilHelpMeGUI $parent) {
		parent::__construct();

		$this->parent = $parent;
		$this->pl = ilHelpMePlugin::getInstance();

		$this->setForm();
	}


	/**
	 *
	 */
	protected function setForm() {
		$this->setFormAction($this->ilCtrl->getFormAction($this->parent, "", "", true));

		$this->addCommandButton("", $this->txt("srsu_close"), "il_help_me_cancel");

		$this->setId("il_help_me_form");
		$this->setShowTopButtons(false);
	}


	/**
	 * @param string $a_var
	 *
	 * @return string
	 */
	protected function txt($a_var) {
		return $this->pl->txt($a_var);
	}
}
