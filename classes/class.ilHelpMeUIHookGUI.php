<?php
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * HelpMe UIHook-GUI
 *
 * @property ilHelpMePlugin $pl
 */
class ilHelpMeUIHookGUI extends ilUIHookPluginGUI {

	use srag\DIC\DIC;


	/**
	 *
	 */
	public function __construct() {

	}


	/**
	 * @param string $a_comp
	 * @param string $a_part
	 * @param array  $a_par
	 *
	 * @return array
	 */
	public function getHTML($a_comp, $a_part, $a_par = []) {
		if ($a_comp === "Services/MainMenu" && $a_part === "main_menu_search") {
			if (ilHelpMeConfigRole::currentUserHasRole()) {
				// Support button
				$tpl = $this->getTemplate("il_help_me_button.html");

				iljQueryUtil::initjQuery();
				self::dic()->tpl()->addJavaScript("Services/Form/js/Form.js");
				self::dic()->tpl()->addJavaScript($this->pl->getDirectory() . "/lib/html2canvas.min.js");
				self::dic()->tpl()->addJavaScript($this->pl->getDirectory() . "/js/ilHelpMe.js");

				$tpl->setCurrentBlock("il_help_me_button");
				$tpl->setVariable("SUPPORT_TXT", $this->txt("srsu_support"));
				$tpl->setVariable("SUPPORT_LINK", self::dic()->ctrl()->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					ilHelpMeGUI::class
				], ilHelpMeGUI::CMD_ADD_SUPPORT, "", true));
				$html = $tpl->get();

				return [ "mode" => self::PREPEND, "html" => $html ];
			}
		}

		if ($a_par["tpl_id"] === "tpl.adm_content.html") {
			if (ilHelpMeConfigRole::currentUserHasRole()) {
				// Modal
				// TODO: Fix after first configure currentUserHasRole false because not yet set, only after this
				ilModalGUI::initJS();

				$modal = ilModalGUI::getInstance();
				$modal->setType(ilModalGUI::TYPE_LARGE);
				$modal->setHeading($this->txt("srsu_support"));

				$modal->setId("il_help_me_modal");

				$html = $modal->getHTML();

				return [ "mode" => self::APPEND, "html" => $html ];
			}
		}

		return [ "mode" => self::KEEP, "html" => "" ];
	}
}
