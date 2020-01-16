<?php

namespace srag\Plugins\HelpMe\Project;

use ilConfirmationGUI;
use ilHelpMePlugin;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectConfigGUI
 *
 * @package           srag\Plugins\HelpMe\Project
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Project\ProjectConfigGUI: srag\Plugins\HelpMe\Project\ProjectsConfigGUI
 */
class ProjectConfigGUI
{

    use DICTrait;
    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const CMD_ADD_PROJECT = "addProject";
    const CMD_BACK = "back";
    const CMD_CREATE_PROJECT = "createProject";
    const CMD_EDIT_PROJECT = "editProject";
    const CMD_UPDATE_PROJECT = "updateProject";
    const CMD_REMOVE_PROJECT_CONFIRM = "removeProjectConfirm";
    const CMD_REMOVE_PROJECT = "removeProject";
    const GET_PARAM_PROJECT_ID = "project_id";
    const TAB_EDIT_PROJECT = "edit_project";
    /**
     * @var ProjectsConfigGUI
     */
    protected $parent;
    /**
     * @var Project
     */
    protected $project;


    /**
     * ProjectConfigGUI constructor
     *
     * @param ProjectsConfigGUI $parent
     */
    public function __construct(ProjectsConfigGUI $parent)
    {
        $this->parent = $parent;
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->project = self::helpMe()->projects()->getProjectById(intval(filter_input(INPUT_GET, self::GET_PARAM_PROJECT_ID)));

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_PROJECT_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_PROJECT:
                    case self::CMD_BACK:
                    case self::CMD_CREATE_PROJECT:
                    case self::CMD_EDIT_PROJECT:
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
        self::dic()->tabs()->clearTargets();

        self::dic()->tabs()->setBackTarget(self::plugin()->translate("projects", ProjectsConfigGUI::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK));

        if ($this->project !== null) {
            if (self::dic()->ctrl()->getCmd() === self::CMD_REMOVE_PROJECT_CONFIRM) {
                self::dic()->tabs()->addTab(self::TAB_EDIT_PROJECT, self::plugin()->translate("remove_project", ProjectsConfigGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_REMOVE_PROJECT_CONFIRM));
            } else {
                self::dic()->tabs()->addTab(self::TAB_EDIT_PROJECT, self::plugin()->translate("edit_project", ProjectsConfigGUI::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_EDIT_PROJECT));

                self::dic()->locator()->addItem($this->project->getTitle(), self::dic()->ctrl()->getLinkTarget($this, self::CMD_EDIT_PROJECT));
            }
        } else {
            $this->project = self::helpMe()->projects()->factory()->newInstance();

            self::dic()->tabs()->addTab(self::TAB_EDIT_PROJECT, self::plugin()->translate("add_project", ProjectsConfigGUI::LANG_MODULE), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_ADD_PROJECT));
        }
    }


    /**
     *
     */
    protected function back()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(ProjectsConfigGUI::class, ProjectsConfigGUI::CMD_LIST_PROJECTS);
    }


    /**
     *
     */
    protected function addProject()/*: void*/
    {
        $form = self::helpMe()->projects()->factory()->newFormInstance($this, $this->project);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function createProject()/*: void*/
    {
        $form = self::helpMe()->projects()->factory()->newFormInstance($this, $this->project);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_PROJECT_ID, $this->project->getProjectId());

        ilUtil::sendSuccess(self::plugin()->translate("added_project", ProjectsConfigGUI::LANG_MODULE, [$this->project->getProjectName()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_PROJECT);
    }


    /**
     *
     */
    protected function editProject()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_PROJECT);

        $form = self::helpMe()->projects()->factory()->newFormInstance($this, $this->project);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateProject()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_EDIT_PROJECT);

        $form = self::helpMe()->projects()->factory()->newFormInstance($this, $this->project);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved_project", ProjectsConfigGUI::LANG_MODULE, [$this->project->getProjectName()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_PROJECT);
    }


    /**
     *
     */
    protected function removeProjectConfirm()/*: void*/
    {
        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("remove_project_confirm", ProjectsConfigGUI::LANG_MODULE, [$this->project->getProjectName()]));

        $confirmation->addItem(self::GET_PARAM_PROJECT_ID, $this->project->getProjectId(), $this->project->getProjectName());

        $confirmation->setConfirm(self::plugin()->translate("remove", ProjectsConfigGUI::LANG_MODULE), self::CMD_REMOVE_PROJECT);
        $confirmation->setCancel(self::plugin()->translate("cancel", ProjectsConfigGUI::LANG_MODULE), self::CMD_BACK);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function removeProject()/*: void*/
    {
        self::helpMe()->projects()->deleteProject($this->project);

        ilUtil::sendSuccess(self::plugin()->translate("removed_project", ProjectsConfigGUI::LANG_MODULE, [$this->project->getProjectName()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }
}
