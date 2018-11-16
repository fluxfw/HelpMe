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
	 * @var string|null
	 */
	protected $project_key;


	/**
	 * ProjectFormGUI constructor
	 *
	 * @param ActiveRecordConfigGUI $parent
	 * @param string                $tab_id
	 * @param string|null           $project_key
	 */
	public function __construct(ActiveRecordConfigGUI $parent, string $tab_id, /*?*/
		string $project_key = NULL) {

		$this->project_key = $project_key;

		parent::__construct($parent, $tab_id);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		switch ($key) {
			case "project_key":
				return $this->project_key;

			case "project_name":
				if ($this->project_key !== NULL) {
					$configProjects = Config::getField(Config::KEY_PROJECTS);

					return $configProjects[$this->project_key];
				}
				break;

			default:
				break;
		}

		return NULL;
	}


	/**
	 * @inheritdoc
	 */
	protected function initAction()/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent, "srsu_project_key", $this->project_key);

		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent));

		self::dic()->ctrl()->setParameter($this->parent, "srsu_project_key", NULL);
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		if ($this->project_key !== NULL) {
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
		$this->setTitle($this->txt($this->project_key !== NULL ? "edit_project" : "add_project"));
	}


	/**
	 * @inheritdoc
	 */
	public function updateForm()/*: void*/ {
		$configProjects = Config::getField(Config::KEY_PROJECTS);

		$project_key = $this->getInput("project_key");

		$project_name = $this->getInput("project_name");

		if ($this->project_key !== NULL) {
			unset($configProjects[$this->project_key]);
		}

		$configProjects[$project_key] = $project_name;

		Config::setField(Config::KEY_PROJECTS, $configProjects);

		$this->project_key = $project_key;
	}


	/**
	 * @return string
	 */
	public function getProjectKey(): string {
		return $this->project_key;
	}
}
