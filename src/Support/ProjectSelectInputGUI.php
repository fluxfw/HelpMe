<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ilSelectInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Ticket\TicketsGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectSelectInputGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectSelectInputGUI extends ilSelectInputGUI {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @param string $a_mode
	 *
	 * @return string
	 */
	public function render(/*string*/
		$a_mode = ""): string {
		if (self::tickets()->isEnabled()) {
			$tpl = self::plugin()->template("project_select_input.html");

			$tpl->setVariable("SELECT", parent::render($a_mode));

			$tpl->setVariable("TICKETS_LINK", self::output()->getHTML(self::dic()->ui()->factory()->link()->standard(self::plugin()
				->translate("show_tickets_of_selected_project", TicketsGUI::LANG_MODULE_TICKETS), self::tickets()->getLink("%project_url_key%"))
				->withOpenInNewViewport(true)));

			return self::output()->getHTML($tpl);
		} else {
			return parent::render($a_mode);
		}
	}
}
