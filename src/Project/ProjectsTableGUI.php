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
	public function getSelectableColumns2(): array {
		$columns = [
			"project_key" => "project_key",
			"project_name" => "project_name",
			"support_link" => "support_link"
		];

		$columns = array_map(function (string $key): array {
			return [
				"id" => $key,
				"default" => true,
				"sort" => ($key !== "support_link")
			];
		}, $columns);

		return $columns;
	}


	/**
	 * @inheritdoc
	 */
	protected function initColumns()/*: void*/ {
		parent::initColumns();

		$this->addColumn($this->txt("actions"));

		$this->setDefaultOrderField("project_name");
		$this->setDefaultOrderDirection("sort");
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
			$support_link = ILIAS_HTTP_PATH . "/goto.php?target=uihk_" . ilHelpMePlugin::PLUGIN_ID . "_" . $project["project_url_key"];

			$project["support_link"] = self::output()->getHTML(self::dic()->ui()->factory()->link()->standard($support_link, $support_link)
				->withOpenInNewViewport(true));

			return $project;
		}, $projects));
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("srsu_projects");
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

		parent::fillRow($row);

		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->txt("actions"));

		$actions->addItem($this->txt("edit_project"), "", $edit_project_link);
		$actions->addItem($this->txt("remove_project"), "", $remove_project_link);

		$this->tpl->setVariable("COLUMN", self::output()->getHTML($actions));
	}
}
