<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ilHelpMeUIHookGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilHelpMeUIHookGUI extends ilUIHookPluginGUI {

	use DICTrait;
	use HelpMeTrait;
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
	public function getHTML(/*string*/
		$a_comp, /*string*/
		$a_part, /*array*/
		$a_par = []): array {
		if ($a_par["tpl_id"] === "Services/MainMenu/tpl.main_menu.html" && $a_part === "template_get") {

			if (self::access()->currentUserHasRole()) {

				$html = $a_par["html"];

				$userlog_pos = stripos($html, '<li id="userlog" class="dropdown">');
				if ($userlog_pos !== false) {

					// Support button
					$tpl = self::plugin()->template("il_help_me_button.html");

					iljQueryUtil::initjQuery();
					self::dic()->mainTemplate()->addJavaScript("Services/Form/js/Form.js");
					self::dic()->mainTemplate()->addJavaScript(self::plugin()->directory() . "/node_modules/html2canvas/dist/html2canvas.min.js");
					self::dic()->mainTemplate()->addJavaScript(self::plugin()->directory() . "/js/HelpMe.js");

					$tpl->setCurrentBlock("il_help_me_button");
					$tpl->setVariable("SUPPORT_TXT", self::plugin()->translate("support", HelpMeSupportGUI::LANG_MODULE_SUPPORT));
					$tpl->setVariable("SUPPORT_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						HelpMeSupportGUI::class
					], HelpMeSupportGUI::CMD_ADD_SUPPORT, "", true));

					$html = substr($html, 0, ($userlog_pos - 1)) . $tpl->get() . substr($html, $userlog_pos);

					return [ "mode" => self::REPLACE, "html" => $html ];
				}
			}
		}

		if ($a_par["tpl_id"] === "tpl.adm_content.html") {

			if (self::access()->currentUserHasRole()) {

				// Modal
				// TODO: Fix after first configure currentUserHasRole false because not yet set, only after this
				// TODO: Modal UIServices
				ilModalGUI::initJS();

				$modal = ilModalGUI::getInstance();
				$modal->setType(ilModalGUI::TYPE_LARGE);
				$modal->setHeading(self::plugin()->translate("support", HelpMeSupportGUI::LANG_MODULE_SUPPORT));

				$modal->setId("il_help_me_modal");

				$html = $modal->getHTML();

				return [ "mode" => self::APPEND, "html" => $html ];
			}
		}

		return [ "mode" => self::KEEP, "html" => "" ];
	}
}
