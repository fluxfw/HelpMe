<?php
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * HelpMe UIHook-GUI
 */
class ilHelpMeUIHookGUI extends ilUIHookPluginGUI {

	use \srag\DICTrait;
	/**
	 * @var ilHelpMePlugin
	 */
	protected $pl;


	/**
	 *
	 */
	public function __construct() {
		$this->pl = ilHelpMePlugin::getInstance();
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
				$tpl = $this->pl->getTemplate("il_help_me_button.html");

				iljQueryUtil::initjQuery();
				$this->tpl->addJavaScript("Services/Form/js/Form.js");
				$this->tpl->addJavaScript($this->pl->getDirectory() . "/lib/html2canvas.min.js");
				$this->tpl->addJavaScript($this->pl->getDirectory() . "/js/ilHelpMe.js");

				$tpl->setCurrentBlock("il_help_me_button");
				$tpl->setVariable("SUPPORT_TXT", $this->txt("srsu_support"));
				$tpl->setVariable("SUPPORT_LINK", $this->ilCtrl->getLinkTargetByClass([
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
				// TODO Fix after first configure currentUserHasRole false because not yet set, only after this
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
