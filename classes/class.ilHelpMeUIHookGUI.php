<?php

use ILIAS\UI\Component\Link\Standard;
use srag\CustomInputGUIs\HelpMe\ScreenshotsInputGUI\ScreenshotsInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Support\IssueTypeSelectInputGUI;
use srag\Plugins\HelpMe\Support\ProjectSelectInputGUI;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Ticket\TicketsGUI;
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
	const SESSION_PROJECT_URL_KEY = ilHelpMePlugin::PLUGIN_ID . "_project_url_key";
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

					$screenshot = new ScreenshotsInputGUI();
					$screenshot->withPlugin(self::plugin());
					$screenshot->initJS();

					self::dic()->mainTemplate()->addCss(substr(self::plugin()->directory(), 2) . "/css/HelpMe.css");

					self::dic()->mainTemplate()->addJavaScript(substr(self::plugin()->directory(), 2) . "/js/HelpMe.min.js", false);

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

					$helpme_js = '<script type="text/javascript" src="' . substr(self::plugin()->directory(), 2) . '/js/HelpMe.min.js"></script>';
					$helpme_js_pos = stripos($html, $helpme_js);
					if ($helpme_js_pos !== false) {

						$support_button = $this->getSupportButton();

						$screenshot = new ScreenshotsInputGUI();
						$screenshot->withPlugin(self::plugin());

						$project_id = ilSession::get(self::SESSION_PROJECT_URL_KEY);

						// Could not use onload code because it not available on all pages
						$html = substr($html, 0, ($helpme_js_pos + strlen($helpme_js))) . '<script>
il.HelpMe.MODAL_TEMPLATE = ' . json_encode($this->getModal()) . ';
il.HelpMe.SUPPORT_BUTTON_TEMPLATE = ' . json_encode($support_button) . ';
il.HelpMe.GET_SHOW_TICKETS_OF_PROJECT_URL = ' . json_encode(self::dic()->ctrl()->getLinkTargetByClass([
								ilUIPluginRouterGUI::class,
								SupportGUI::class,
								ProjectSelectInputGUI::class
							], ProjectSelectInputGUI::CMD_GET_SHOW_TICKETS_LINK_OF_PROJECT, "", true)) . ';
il.HelpMe.GET_ISSUE_TYPES_OF_PROJECT_URL = ' . json_encode(self::dic()->ctrl()->getLinkTargetByClass([
								ilUIPluginRouterGUI::class,
								SupportGUI::class,
								IssueTypeSelectInputGUI::class
							], IssueTypeSelectInputGUI::CMD_GET_ISSUE_TYPES_OF_PROJECT, "", true)) . ';
il.HelpMe.init();
' . $screenshot->getJSOnLoadCode() . '
' . ($project_id !== null ? 'il.HelpMe.autoOpen = true;' : '') . '
							</script>' . substr($html, $helpme_js_pos + strlen($helpme_js));

						return [ "mode" => self::REPLACE, "html" => $html ];
					}
				}
			}
		}

		return parent::getHTML($a_comp, $a_part, $a_par);
	}


	/**
	 * @return string
	 */
	public function getSupportButton(): string {
		$buttons = [
			self::dic()->ui()->factory()->link()->standard(self::plugin()->translate("support", SupportGUI::LANG_MODULE_SUPPORT), self::dic()->ctrl()
				->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					SupportGUI::class
				], SupportGUI::CMD_ADD_SUPPORT, "", true))
		];

		if (self::tickets()->isEnabled()) {
			$buttons[] = self::dic()->ui()->factory()->link()->standard(self::plugin()
				->translate("show_tickets", SupportGUI::LANG_MODULE_SUPPORT), self::tickets()->getLink());

			$support_button_tpl = self::plugin()->template("helpme_support_button_dropdown.html");
		} else {
			$support_button_tpl = self::plugin()->template("helpme_support_button.html");
		}

		$support_button_tpl->setVariable("TXT_SUPPORT", self::plugin()->translate("support", SupportGUI::LANG_MODULE_SUPPORT));

		$support_button_tpl->setVariable("BUTTONS", self::output()->getHTML(array_map(function (Standard $button): string {
			return self::output()->getHTML([
				"<li>",
				$button,
				"</li>"
			]);
		}, $buttons)));

		return self::output()->getHTML($support_button_tpl);
	}


	/**
	 * @return string
	 */
	protected function getModal(): string {
		$modal = self::output()->getHTML(self::dic()->ui()->factory()->modal()->roundtrip(self::plugin()
			->translate("support", SupportGUI::LANG_MODULE_SUPPORT), self::dic()->ui()->factory()->legacy("")));

		// HelpMe needs so patches on the new roundtrip modal ui

		// Large modal
		$modal = str_replace('<div class="modal-dialog"', '<div class="modal-dialog modal-lg"', $modal);

		// Buttons will delivered over the form gui
		$modal = str_replace('<div class="modal-footer">', '<div class="modal-footer" style="display:none;">', $modal);

		return $modal;
	}


	/**
	 *
	 */
	public function gotoHook()/*: void*/ {
		$target = filter_input(INPUT_GET, "target");

		$matches = [];
		preg_match("/^uihk_" . ilHelpMePlugin::PLUGIN_ID . "(_(.*))?/uim", $target, $matches);

		if (is_array($matches) && count($matches) >= 1) {
			$project_url_key = $matches[2];

			if ($project_url_key === null) {
				$project_url_key = "";
			}

			if (strpos($project_url_key, "tickets") === 0) {
				// Tickets
				$project_url_key = substr($project_url_key, strlen("tickets"));
				if ($project_url_key[0] === "_") {
					$project_url_key = substr($project_url_key, 1);
				}

				self::dic()->ctrl()->setTargetScript("ilias.php"); // Fix ILIAS 5.3 bug
				self::dic()->ctrl()->initBaseClass(ilUIPluginRouterGUI::class); // Fix ILIAS bug

				self::dic()->ctrl()->setParameterByClass(TicketsGUI::class, "project_url_key", $project_url_key);

				self::dic()->ctrl()->redirectByClass([ ilUIPluginRouterGUI::class, TicketsGUI::class ], TicketsGUI::CMD_SET_PROJECT_FILTER);
			} else {
				// Support
				ilSession::set(self::SESSION_PROJECT_URL_KEY, $project_url_key);

				self::dic()->ctrl()->redirectToURL("/");
			}
		}
	}
}
