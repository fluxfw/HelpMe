<?php

namespace srag\Plugins\HelpMe\Config\Projects;

use ilAdvancedSelectionListGUI;
use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilLinkButton;
use srag\ActiveRecordConfig\ActiveRecordConfigTableGUI;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectsTableGUI
 *
 * @package srag\Plugins\HelpMe\Config\Projects
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectsTableGUI extends ActiveRecordConfigTableGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 *
	 */
	protected function initTable()/*: void*/ {
		parent::initTable();

		$parent = $this->getParentObject();

		$add_project = ilLinkButton::getInstance();
		$add_project->setCaption($this->txt("add_project"), false);
		$add_project->setUrl(self::dic()->ctrl()->getLinkTarget($parent, ilHelpMeConfigGUI::CMD_ADD_PROJECT));
		self::dic()->toolbar()->addButtonInstance($add_project);

		$this->setRowTemplate("projects_table_row.html", self::plugin()->directory());
	}


	/**
	 *
	 */
	protected function initData()/*: void*/ {
		$configProjects = Config::getProjects();

		$this->setData(array_values(array_map(function (string $project_key, string $project_name): array {
			return [
				"project_key" => $project_key,
				"project_name" => $project_name
			];
		}, array_keys($configProjects), $configProjects)));
	}


	/**
	 *
	 */
	protected function initColumns()/*: void*/ {
		$this->addColumn($this->txt("key"));
		$this->addColumn($this->txt("name"));
		$this->addColumn($this->txt("actions"));
	}


	/**
	 * @param array $project
	 */
	protected /*abstract*/
	function fillRow(/*array*/
		$project) {
		$parent = $this->getParentObject();

		self::dic()->ctrl()->setParameter($parent, "srsu_project_key", $project["project_key"]);
		$edit_project_link = self::dic()->ctrl()->getLinkTarget($parent, ilHelpMeConfigGUI::CMD_EDIT_PROJECT);
		$remove_project_link = self::dic()->ctrl()->getLinkTarget($parent, ilHelpMeConfigGUI::CMD_REMOVE_PROJECT_CONFIRM);
		self::dic()->ctrl()->setParameter($parent, "srsu_project_key", NULL);

		$this->tpl->setVariable("PROJECT_KEY", $project["project_key"]);

		$this->tpl->setVariable("PROJECT_NAME", $project["project_name"]);

		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->txt("actions"));

		$actions->addItem($this->txt("edit_project"), "", $edit_project_link);
		$actions->addItem($this->txt("remove_project"), "", $remove_project_link);

		$this->tpl->setVariable("ACTIONS", $actions->getHTML());
	}
}
