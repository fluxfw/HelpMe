<?php

namespace srag\Plugins\HelpMe\Project;

use ilAdvancedSelectionListGUI;
use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilLinkButton;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfigTableGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectsTableGUI
 *
 * @package srag\Plugins\HelpMe\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectsTableGUI extends ActiveRecordConfigTableGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const ROW_TEMPLATE = "projects_table_row.html";


	/**
	 * @inheritdoc
	 */
	protected function getColumnValue(/*string*/
		$column, /*array*/
		$row, /*bool*/
		$raw_export = false): string {
		switch ($column) {
			default:
				$column = $row[$column];
				break;
		}

		return strval($column);
	}


	/**
	 * @inheritdoc
	 */
	public function getSelectableColumns(): array {
		$columns = [];

		return $columns;
	}


	/**
	 * @inheritdoc
	 */
	protected function initColumns()/*: void*/ {
		$this->addColumn($this->txt("project_key"));
		$this->addColumn($this->txt("project_name"));
		$this->addColumn($this->txt("support_link"));
		$this->addColumn($this->txt("actions"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$add_project = ilLinkButton::getInstance();
		$add_project->setCaption($this->txt("add_project"), false);
		$add_project->setUrl(self::dic()->ctrl()->getLinkTarget($this->parent_obj, ilHelpMeConfigGUI::CMD_ADD_PROJECT));
		self::dic()->toolbar()->addButtonInstance($add_project);
	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$projects = self::projects()->getProjectsArray();

		$this->setData(array_map(function (array $project): array {
			$project["support_link"] = ILIAS_HTTP_PATH . "/goto.php?target=uihk_" . ilHelpMePlugin::PLUGIN_ID . "_" . $project["project_key"];

			return $project;
		}, $projects));
	}


	/**
	 * @param array $row
	 */
	protected function fillRow(/*array*/
		$row)/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent_obj, "srsu_project_id", $row["project_id"]);
		$edit_project_link = self::dic()->ctrl()->getLinkTarget($this->parent_obj, ilHelpMeConfigGUI::CMD_EDIT_PROJECT);
		$remove_project_link = self::dic()->ctrl()->getLinkTarget($this->parent_obj, ilHelpMeConfigGUI::CMD_REMOVE_PROJECT_CONFIRM);
		self::dic()->ctrl()->setParameter($this->parent_obj, "srsu_project_id", NULL);

		$this->tpl->setVariable("PROJECT_KEY", $row["project_key"]);

		$this->tpl->setVariable("PROJECT_NAME", $row["project_name"]);

		$support_link = self::dic()->ui()->factory()->link()->standard($row["support_link"], $row["support_link"])->withOpenInNewViewport(true);
		$this->tpl->setVariable("SUPPORT_LINK", self::output()->getHTML($support_link));

		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->txt("actions"));

		$actions->addItem($this->txt("edit_project"), "", $edit_project_link);
		$actions->addItem($this->txt("remove_project"), "", $remove_project_link);

		$this->tpl->setVariable("ACTIONS", self::output()->getHTML($actions));
	}
}
