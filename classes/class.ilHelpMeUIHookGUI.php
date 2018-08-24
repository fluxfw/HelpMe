<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\HelpMeConfigRole;

/**
 * Class ilHelpMeUIHookGUI
 */
class ilHelpMeUIHookGUI extends ilUIHookPluginGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * ilHelpMeUIHookGUI constructor
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
	public function getHTML($a_comp, $a_part, $a_par = []): array {
		if ($a_comp === "Services/MainMenu" && $a_part === "main_menu_search") {
			if (HelpMeConfigRole::currentUserHasRole()) {
				// Support button
				$tpl = self::template("il_help_me_button.html");

				iljQueryUtil::initjQuery();
				self::dic()->tpl()->addJavaScript("Services/Form/js/Form.js");
				self::dic()->tpl()->addJavaScript(self::directory() . "/node_modules/html2canvas/dist/html2canvas.min.js");
				self::dic()->tpl()->addJavaScript(self::directory() . "/js/ilHelpMe.js");

				$tpl->setCurrentBlock("il_help_me_button");
				$tpl->setVariable("SUPPORT_TXT", self::translate("srsu_support"));
				$tpl->setVariable("SUPPORT_LINK", self::dic()->ctrl()->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					ilHelpMeGUI::class
				], ilHelpMeGUI::CMD_ADD_SUPPORT, "", true));
				$html = $tpl->get();

				return [ "mode" => self::PREPEND, "html" => $html ];
			}
		}

		if ($a_par["tpl_id"] === "tpl.adm_content.html") {
			if (HelpMeConfigRole::currentUserHasRole()) {
				// Modal
				// TODO: Fix after first configure currentUserHasRole false because not yet set, only after this
				ilModalGUI::initJS();

				$modal = ilModalGUI::getInstance();
				$modal->setType(ilModalGUI::TYPE_LARGE);
				$modal->setHeading(self::translate("srsu_support"));

				$modal->setId("il_help_me_modal");

				$html = $modal->getHTML();

				return [ "mode" => self::APPEND, "html" => $html ];
			}
		}

		return [ "mode" => self::KEEP, "html" => "" ];
	}
}
