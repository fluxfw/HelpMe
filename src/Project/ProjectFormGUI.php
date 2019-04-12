<?php

namespace srag\Plugins\HelpMe\Project;

use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfigFormGUI;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfigGUI;
use srag\CustomInputGUIs\HelpMe\MultiLineInputGUI\MultiLineInputGUI;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectFormGUI
 *
 * @package srag\Plugins\HelpMe\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectFormGUI extends ActiveRecordConfigFormGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CONFIG_CLASS_NAME = Config::class;
	/**
	 * @var Project|null
	 */
	protected $project;


	/**
	 * ProjectFormGUI constructor
	 *
	 * @param ActiveRecordConfigGUI $parent
	 * @param string                $tab_id
	 * @param Project|null          $project
	 */
	public function __construct(ActiveRecordConfigGUI $parent, string $tab_id, /*?*/
		Project $project = null) {

		$this->project = $project;

		parent::__construct($parent, $tab_id);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		if ($this->project !== null) {
			switch ($key) {
				case "project_key":
					return $this->project->getProjectKey();

				case "project_url_key":
					return $this->project->getProjectUrlKey();

				case "project_name":
					return $this->project->getProjectName();

				case "project_issue_types":
					return $this->project->getProjectIssueTypes();

				default:
					break;
			}
		} else {
			switch ($key) {
				case "project_issue_types":
					return [
						[
							"issue_type" => Project::DEFAULT_ISSUE_TYPE,
							"fix_version" => Project::DEFAULT_FIX_VERSION
						]
					];

				default:
					break;
			}
		}

		return null;
	}


	/**
	 * @inheritdoc
	 */
	protected function initAction()/*: void*/ {
		if ($this->project !== null) {
			self::dic()->ctrl()->setParameter($this->parent, "srsu_project_id", $this->project->getProjectId());
		}

		parent::initAction();

		self::dic()->ctrl()->setParameter($this->parent, "srsu_project_id", null);
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		if ($this->project !== null) {
			$this->addCommandButton(ilHelpMeConfigGUI::CMD_UPDATE_PROJECT, $this->txt("save"));
		} else {
			$this->addCommandButton(ilHelpMeConfigGUI::CMD_CREATE_PROJECT, $this->txt("add"));
		}

		$this->addCommandButton($this->parent->getCmdForTab(ilHelpMeConfigGUI::TAB_PROJECTS), $this->txt("cancel"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		self::tickets()->showUsageConfigHint();

		$this->fields = [
			"project_key" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"project_url_key" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"project_name" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"project_issue_types" => [
				self::PROPERTY_CLASS => MultiLineInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_MULTI => true,
				"setShowLabel" => true,
				self::PROPERTY_SUBITEMS => [
					"issue_type" => [
						self::PROPERTY_CLASS => ilTextInputGUI::class,
						self::PROPERTY_REQUIRED => true,
						"setTitle" => $this->txt("project_issue_type")
					],
					"fix_version" => [
						self::PROPERTY_CLASS => ilTextInputGUI::class,
						self::PROPERTY_REQUIRED => false,
						"setTitle" => $this->txt("project_fix_version")
					]
				],
				"setInfo" => self::output()->getHTML(self::dic()->ui()->factory()->listing()->descriptive([
					$this->txt("project_issue_type") => $this->txt("project_issue_type_info"),
					$this->txt("project_fix_version") => $this->txt("project_fix_version_info")
				]))
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {
		$this->setTitle($this->txt($this->project !== null ? "edit_project" : "add_project"));
	}


	/**
	 * @inheritdoc
	 */
	public function storeForm(): bool {
		if ($this->project === null) {
			$this->project = self::projects()->factory()->newInstance();
		}

		if (!parent::storeForm()) {
			return false;
		}

		self::projects()->storeInstance($this->project);

		return true;
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			case "project_key":
				$this->project->setProjectKey(strval($value));
				break;

			case "project_url_key":
				$this->project->setProjectUrlKey(strval($value));
				break;

			case "project_name":
				$this->project->setProjectName(strval($value));
				break;

			case "project_issue_types":
				$this->project->setProjectIssueTypes(array_values($value));
				break;

			default:
				break;
		}
	}


	/**
	 * @return Project
	 */
	public function getProject(): Project {
		return $this->project;
	}
}
