<?php
require_once "Services/UIComponent/classes/class.ilUIHookPluginGUI.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/class.ilHelpMePlugin.php";
require_once "Services/jQuery/classes/class.iljQueryUtil.php";
require_once "Services/UIComponent/Modal/classes/class.ilModalGUI.php";

/**
 * HelpMe UIHook-GUI
 */
class ilHelpMeUIHookGUI extends ilUIHookPluginGUI {

	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilHelpMeUIHookGUI
	 */
	protected $pl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;


	function __construct() {
		/**
		 * var ilCtrl $ilCtrl
		 * var ilTemplate $tpl
		 */

		global $ilCtrl, $tpl;

		$this->ctrl = $ilCtrl;
		$this->pl = ilHelpMePlugin::getInstance();
		$this->tpl = $tpl;
	}


	/**
	 * @param string $a_comp
	 * @param string $a_part
	 * @param array  $a_par
	 */
	function getHTML($a_comp, $a_part, $a_par = array()) {
		if ($a_comp === "Services/MainMenu" && $a_part === "main_menu_search") {

			$html = "";

			$tpl = $this->pl->getTemplate("menu_support_button.html", true, true);

			iljQueryUtil::initjQuery();
			ilModalGUI::initJS();
			$this->tpl->addJavaScript("Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/js/ilHelpMe.js");
			$this->tpl->addOnLoadCode("il.HelpMe.init();");

			$tpl->setCurrentBlock("linkBlock");
			$tpl->setVariable("SUPPORT_TXT", $this->txt("srsu_support"));

			$html = $tpl->get();

			return [ "mode" => ilUIHookPluginGUI::PREPEND, "html" => $html ];
		}
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
