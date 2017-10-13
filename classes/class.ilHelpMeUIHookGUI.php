<?php
require_once "Services/UIComponent/classes/class.ilUIHookPluginGUI.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/EnrolMembers/classes/class.ilEnrolMembersAccess.php";

/**
 * HelpMe UIHook-GUI
 */
class ilHelpMeUIHookGUI extends ilUIHookPluginGUI {

	/**
	 * @var ilCtrl
	 */
	protected $ctrl;


	function __construct() {
		/**
		 * var ilCtrl $ilCtrl
		 */

		global $ilCtrl;

		$this->ctrl = $ilCtrl;
	}


	/**
	 * Modify GUI objects, before they generate ouput
	 *
	 * @param string $a_comp component
	 * @param string $a_part string that identifies the part of the UI that is handled
	 * @param array  $a_par  array of parameters (depend on $a_comp and $a_part)
	 */
	function modifyGUI($a_comp, $a_part, $a_par = array()) {

	}


	/**
	 * @param string $a_var
	 *
	 * @return string
	 */
	protected function txt($a_var) {
		return $this->getPluginObject()->txt($a_var);
	}
}
