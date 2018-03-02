<?php
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/vendor/autoload.php";
require_once "Services/UIComponent/classes/class.ilUIHookPluginGUI.php";
require_once "Services/jQuery/classes/class.iljQueryUtil.php";
require_once "Services/UIComponent/Modal/classes/class.ilModalGUI.php";
require_once "Services/UIComponent/classes/class.ilUIPluginRouterGUI.php";

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


	function __construct() {
		global $DIC;

		$this->ctrl = $DIC->ctrl();
		$this->pl = ilHelpMePlugin::getInstance();
	}


	/**
	 * @param string $a_comp
	 * @param string $a_part
	 * @param array  $a_par
	 */
	function getHTML($a_comp, $a_part, $a_par = []) {
		global $DIC;

		if ($a_comp === "Services/MainMenu" && $a_part === "main_menu_search") {
			if (ilHelpMeConfigRole::currentUserHasRole()) {
				// Support button
				$tpl = $this->pl->getTemplate("il_help_me_button.html", true, true);

				$main_tpl = $DIC->ui()->mainTemplate();
				iljQueryUtil::initjQuery();
				$main_tpl->addJavaScript("Services/Form/js/Form.js");
				$main_tpl->addJavaScript($this->pl->getDirectory() . "/lib/html2canvas.min.js");
				$main_tpl->addJavaScript($this->pl->getDirectory() . "/js/ilHelpMe.js");

				$tpl->setCurrentBlock("il_help_me_button");
				$tpl->setVariable("SUPPORT_TXT", $this->txt("srsu_support"));
				$tpl->setVariable("SUPPORT_LINK", $this->ctrl->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					ilHelpMeGUI::class
				], ilHelpMeGUI::CMD_ADD_SUPPORT, "", true));
				$html = $tpl->get();

				return [ "mode" => ilUIHookPluginGUI::PREPEND, "html" => $html ];
			}
		}

		if ($a_par["tpl_id"] === "tpl.adm_content.html") {
			if (ilHelpMeConfigRole::currentUserHasRole()) {
				// Modal
				// TODO Fix after first configure currentUserHasRole false because not yet set
				ilModalGUI::initJS();

				$modal = ilModalGUI::getInstance();
				$modal->setType(ilModalGUI::TYPE_LARGE);
				$modal->setHeading($this->txt("srsu_support"));

				$modal->setId("il_help_me_modal");

				$html = $modal->getHTML();

				return [ "mode" => ilUIHookPluginGUI::APPEND, "html" => $html ];
			}
		}

		return [ "mode" => ilUIHookPluginGUI::KEEP, "html" => "" ];
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
