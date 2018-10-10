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
	const MAIN_TEMPLATE_ID = "tpl.main.html";
	const MAIN_MENU_TEMPLATE_ID = "Services/MainMenu/tpl.main_menu.html";
	const STARTUP_SCREEN_TEMPLATE_ID = "Services/Init/tpl.startup_screen.html";
	const TEMPLATE_ADD = "template_add";
	const TEMPLATE_GET = "template_get";
	const TEMPLATE_SHOW = "template_show";
	const PART_1 = "a";
	const PART_2 = "b";
	/**
	 * @var bool[]
	 */
	protected static $load = [];


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
		if (!self::$load[self::PART_1]) {

			if (($a_par["tpl_id"] === self::MAIN_MENU_TEMPLATE_ID && $a_part === self::TEMPLATE_GET)
				|| ($a_par["tpl_id"] === self::STARTUP_SCREEN_TEMPLATE_ID && $a_part === self::TEMPLATE_ADD)) {

				self::$load[self::PART_1] = true;

				if (self::access()->currentUserHasRole()) {

					ilModalGUI::initJS();

					self::dic()->mainTemplate()->addJavaScript(self::plugin()->directory() . "/node_modules/html2canvas/dist/html2canvas.min.js");

					self::dic()->mainTemplate()->addJavaScript(self::plugin()->directory() . "/js/HelpMe.js", false);

					// Fix some pages may not load Form.js
					self::dic()->mainTemplate()->addJavaScript("Services/Form/js/Form.js");
				}
			}
		}

		if (!self::$load[self::PART_2]) {

			if ($a_par["tpl_id"] === self::MAIN_TEMPLATE_ID && $a_part === self::TEMPLATE_SHOW) {

				self::$load[self::PART_2] = true;

				if (self::access()->currentUserHasRole()) {

					$html = $a_par["html"];

					$helpme_js = '<script type="text/javascript" src="' . self::plugin()->directory() . '/js/HelpMe.js"></script>';
					$helpme_js_pos = stripos($html, $helpme_js);
					if ($helpme_js_pos !== false) {

						$support_button_tpl = self::plugin()->template("helpme_support_button.html");
						$support_button_tpl->setVariable("TXT_SUPPORT", self::plugin()->translate("support", HelpMeSupportGUI::LANG_MODULE_SUPPORT));
						$support_button_tpl->setVariable("SUPPORT_LINK", self::dic()->ctrl()->getLinkTargetByClass([
							ilUIPluginRouterGUI::class,
							HelpMeSupportGUI::class
						], HelpMeSupportGUI::CMD_ADD_SUPPORT, "", true));

						$screenshot_tpl = self::plugin()->template("helpme_screenshot.html");
						$screenshot_tpl->setVariable("TXT_DELETE_SCREENSHOT", self::plugin()
							->translate("delete_screenshot", HelpMeSupportGUI::LANG_MODULE_SUPPORT));

						// TODO: Modal UIServices
						$modal = ilModalGUI::getInstance();
						$modal->setType(ilModalGUI::TYPE_LARGE);
						$modal->setHeading(self::plugin()->translate("support", HelpMeSupportGUI::LANG_MODULE_SUPPORT));

						$html = substr($html, 0, ($helpme_js_pos + strlen($helpme_js))) . '<script>
il.HelpMe.MODAL_TEMPLATE = ' . json_encode($modal->getHTML()) . ';
il.HelpMe.PAGE_SCREENSHOT_NAME = ' . json_encode(self::plugin()->translate("page_screenshot", HelpMeSupportGUI::LANG_MODULE_SUPPORT)) . ';
il.HelpMe.SCREENSHOT_TEMPLATE = ' . json_encode($screenshot_tpl->get()) . ';
il.HelpMe.SUPPORT_BUTTON_TEMPLATE = ' . json_encode($support_button_tpl->get()) . ';
il.HelpMe.init();
							</script>' . substr($html, $helpme_js_pos + strlen($helpme_js));

						return [ "mode" => self::REPLACE, "html" => $html ];
					}
				}
			}
		}

		return [ "mode" => self::KEEP, "html" => "" ];
	}
}
