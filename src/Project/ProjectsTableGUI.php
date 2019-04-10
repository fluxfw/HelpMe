<?php

namespace srag\Plugins\HelpMe\Project;

use ilHelpMeConfigGUI;
use ilHelpMePlugin;
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
		$this->setDefaultOrderDirection("asc");
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_project"), self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, ilHelpMeConfigGUI::CMD_ADD_PROJECT)));
	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$projects = self::projects()->getProjectsArray();

		$this->setData(array_map(function (array $project): array {
			$support_link = self::supports()->getLink($project["project_url_key"]);

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

		parent::fillRow($row);

		$this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
			self::dic()->ui()->factory()->button()->shy($this->txt("edit_project"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, ilHelpMeConfigGUI::CMD_EDIT_PROJECT)),
			self::dic()->ui()->factory()->button()->shy($this->txt("remove_project"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, ilHelpMeConfigGUI::CMD_REMOVE_PROJECT_CONFIRM))
		])->withLabel($this->txt("actions"))));
	}
}
