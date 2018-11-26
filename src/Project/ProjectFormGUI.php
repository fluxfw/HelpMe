<?php

namespace srag\Plugins\HelpMe\Project;

use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfigFormGUI;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfigGUI;
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
		Project $project = NULL) {

		$this->project = $project;

		parent::__construct($parent, $tab_id);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		if ($this->project !== NULL) {
			switch ($key) {
				case "project_key":
					return $this->project->getProjectKey();

				case "project_name":
					return $this->project->getProjectName();
					break;

				default:
					break;
			}
		}

		return NULL;
	}


	/**
	 * @inheritdoc
	 */
	protected function initAction()/*: void*/ {
		if ($this->project !== NULL) {
			self::dic()->ctrl()->setParameter($this->parent, "srsu_project_id", $this->project->getProjectId());
		}

		parent::initAction();

		self::dic()->ctrl()->setParameter($this->parent, "srsu_project_id", NULL);
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		if ($this->project !== NULL) {
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
		$this->fields = [
			"project_key" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"project_name" => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function initTile()/*: void*/ {
		$this->setTitle($this->txt($this->project !== NULL ? "edit_project" : "add_project"));
	}


	/**
	 * @inheritdoc
	 */
	public function storeForm()/*: bool*/ {
		if ($this->project === NULL) {
			$this->project = new Project();
		}

		if (!parent::storeForm()) {
			return false;
		}

		$this->project->store();

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

			case "project_name":
				$this->project->setProjectName(strval($value));
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
