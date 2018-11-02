<?php

namespace srag\Plugins\HelpMe\Config\Projects;

use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\ActiveRecordConfigFormGUI;
use srag\ActiveRecordConfig\ActiveRecordConfigGUI;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectFormGUI
 *
 * @package srag\Plugins\HelpMe\Config\Projects;
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectFormGUI extends ActiveRecordConfigFormGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
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
	 *
	 */
	protected function initForm()/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent, "srsu_project_key", $this->project_key);
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent));
		self::dic()->ctrl()->setParameter($this->parent, "srsu_project_key", NULL);

		$this->setTitle($this->txt($this->project_key !== NULL ? "edit_project" : "add_project"));

		if ($this->project_key !== NULL) {
			$this->addCommandButton(ilHelpMeConfigGUI::CMD_UPDATE_PROJECT, $this->txt("save"));
		} else {
			$this->addCommandButton(ilHelpMeConfigGUI::CMD_CREATE_PROJECT, $this->txt("add"));
		}
		$this->addCommandButton($this->parent->getCmdForTab(ilHelpMeConfigGUI::TAB_PROJECTS), $this->txt("cancel"));

		$key = new ilTextInputGUI($this->txt("key"), "srsu_project_key");
		$key->setRequired(true);
		if ($this->project_key !== NULL) {
			$key->setValue($this->project_key);
		}
		$this->addItem($key);

		$name = new ilTextInputGUI($this->txt("name"), "srsu_project_name");
		$name->setRequired(true);
		if ($this->project_key !== NULL) {
			$configProjects = Config::getProjects();
			$name->setValue($configProjects[$this->project_key]);
		}
		$this->addItem($name);
	}


	/**
	 * @inheritdoc
	 */
	public function updateConfig()/*: void*/ {
		$configProjects = Config::getProjects();

		$project_key = $this->getInput("srsu_project_key");

		$project_name = $this->getInput("srsu_project_name");

		if ($this->project_key !== NULL) {
			unset($configProjects[$this->project_key]);
		}

		$configProjects[$project_key] = $project_name;

		Config::setProjects($configProjects);

		$this->project_key = $project_key;
	}


	/**
	 * @return string
	 */
	public function getProjectKey(): string {
		return $this->project_key;
	}
}
