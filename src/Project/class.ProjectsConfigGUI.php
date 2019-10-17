<?php

namespace srag\Plugins\HelpMe\Project;

use ilConfirmationGUI;
use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Ticket\TicketsGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectsConfigGUI
 *
 * @package           srag\Plugins\HelpMe\Project
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Project\ProjectsConfigGUI: ilHelpMeConfigGUI
 */
class ProjectsConfigGUI
{

    use DICTrait;
    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TAB_PROJECTS = "projects";
    const CMD_ADD_PROJECT = "addProject";
    const CMD_CREATE_PROJECT = "createProject";
    const CMD_EDIT_PROJECT = "editProject";
    const CMD_HIDE_USAGE = "hideUsage";
    const CMD_LIST_PROJECTS = "listProjects";
    const CMD_UPDATE_PROJECT = "updateProject";
    const CMD_REMOVE_PROJECT_CONFIRM = "removeProjectConfirm";
    const CMD_REMOVE_PROJECT = "removeProject";
    const LANG_MODULE_PROJECTS = "projects";


    /**
     * ProjectsConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_PROJECT:
                    case self::CMD_CREATE_PROJECT:
                    case self::CMD_EDIT_PROJECT:
                    case self::CMD_HIDE_USAGE:
                    case self::CMD_LIST_PROJECTS:
                    case self::CMD_UPDATE_PROJECT:
                    case self::CMD_REMOVE_PROJECT:
                    case self::CMD_REMOVE_PROJECT_CONFIRM:
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
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_PROJECTS);

        self::tickets()->showUsageConfigHint();
    }


    /**
     * @param string $cmd
     *
     * @return ProjectsTableGUI
     */
    protected function getProjectsTable(string $cmd = self::CMD_LIST_PROJECTS) : ProjectsTableGUI
    {
        $table = new ProjectsTableGUI($this, $cmd);

        return $table;
    }


    /**
     *
     */
    protected function listProjects()/*: void*/
    {
        $table = $this->getProjectsTable();

        self::output()->output($table);
    }


    /**
     * @param Project $project
     *
     * @return ProjectFormGUI
     */
    protected function getProjectForm(Project $project) : ProjectFormGUI
    {
        $form = new ProjectFormGUI($this, $project);

        return $form;
    }


    /**
     *
     */
    protected function addProject()/*: void*/
    {
        $form = $this->getProjectForm(self::projects()->factory()->newInstance());

        self::output()->output($form);
    }


    /**
     *
     */
    protected function createProject()/*: void*/
    {
        $form = $this->getProjectForm(self::projects()->factory()->newInstance());

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("added_project", self::LANG_MODULE_PROJECTS, [$form->getObject()->getProjectName()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_PROJECTS);
    }


    /**
     *
     */
    protected function editProject()/*: void*/
    {
        $project_id = intval(filter_input(INPUT_GET, "srsu_project_id"));
        $project = self::projects()->getProjectById($project_id);

        $form = $this->getProjectForm($project);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateProject()/*: void*/
    {
        $project_id = intval(filter_input(INPUT_GET, "srsu_project_id"));
        $project = self::projects()->getProjectById($project_id);

        $form = $this->getProjectForm($project);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved_project", self::LANG_MODULE_PROJECTS, [$form->getObject()->getProjectName()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_PROJECTS);
    }


    /**
     *
     */
    protected function removeProjectConfirm()/*: void*/
    {
        $project_id = intval(filter_input(INPUT_GET, "srsu_project_id"));
        $project = self::projects()->getProjectById($project_id);

        $confirmation = new ilConfirmationGUI();

        self::dic()->ctrl()->setParameter($this, "srsu_project_id", $project->getProjectId());
        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));
        self::dic()->ctrl()->setParameter($this, "srsu_project_id", null);

        $confirmation->setHeaderText(self::plugin()->translate("remove_project_confirm", self::LANG_MODULE_PROJECTS, [$project->getProjectName()]));

        $confirmation->addItem("srsu_project_id", $project->getProjectId(), $project->getProjectName());

        $confirmation->setConfirm(self::plugin()->translate("remove", self::LANG_MODULE_PROJECTS), self::CMD_REMOVE_PROJECT);
        $confirmation->setCancel(self::plugin()->translate("cancel", self::LANG_MODULE_PROJECTS), self::CMD_LIST_PROJECTS);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function removeProject()/*: void*/
    {
        $project_id = intval(filter_input(INPUT_GET, "srsu_project_id"));
        $project = self::projects()->getProjectById($project_id);

        self::projects()->deleteProject($project);

        ilUtil::sendSuccess(self::plugin()->translate("removed_project", self::LANG_MODULE_PROJECTS, [$project->getProjectName()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_PROJECTS);
    }


    /**
     *
     */
    protected function hideUsage()/*: void*/
    {
        $usage_id = filter_input(INPUT_GET, TicketsGUI::GET_PARAM_USAGE_ID);

        if (!empty($usage_id)) {
            $usage_hidden = Config::getField(Config::KEY_USAGE_HIDDEN);
            $usage_hidden[$usage_id] = true;
            Config::setField(Config::KEY_USAGE_HIDDEN, $usage_hidden);

            ilUtil::sendSuccess(self::plugin()->translate("usage_hidden", ilHelpMeConfigGUI::LANG_MODULE_CONFIG), true);
        }

        self::dic()->ctrl()->redirectByClass(ilHelpMeConfigGUI::class);
    }
}
