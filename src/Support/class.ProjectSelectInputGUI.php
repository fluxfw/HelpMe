<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ilSelectInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectSelectInputGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Support\ProjectSelectInputGUI: srag\Plugins\HelpMe\Support\SupportGUI
 */
class ProjectSelectInputGUI extends ilSelectInputGUI {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CMD_GET_SHOW_TICKETS_LINK_OF_PROJECT = "getShowTicketsLinkOfProject";
	/**
	 * @var SupportFormGUI
	 */
	public $parent_gui;


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

			$tpl->setVariable("SHOW_TICKETS_LINK", $this->getShowTicketsLink($this->parent_gui->getProject()));

			return self::output()->getHTML($tpl);
		}

		return parent::render($a_mode);
	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch (strtolower($next_class)) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_GET_SHOW_TICKETS_LINK_OF_PROJECT:
						$this->{$cmd}();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 */
	protected function getShowTicketsLinkOfProject()/*: void*/ {
		$project_url_key = filter_input(INPUT_GET, "project_url_key");

		$project = self::projects()->getProjectByUrlKey($project_url_key);

		self::output()->output($this->getShowTicketsLink($project));
	}


	/**
	 * @param Project|null $project
	 *
	 * @return string
	 */
	protected function getShowTicketsLink(/*?*/
		Project $project = null): string {
		if (self::tickets()->isEnabled() && $project !== null && $project->isProjectShowTickets()) {

			return self::output()->getHTML(self::dic()->ui()->factory()->link()->standard(self::plugin()
				->translate("show_tickets_of_selected_project", SupportFormGUI::LANG_MODULE), self::tickets()->getLink($project->getProjectUrlKey()))
				->withOpenInNewViewport(true));
		}

		return "";
	}
}
