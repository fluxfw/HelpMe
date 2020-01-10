<?php

namespace srag\Plugins\HelpMe\Project;

use ilHelpMePlugin;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\TableGUI\TableGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectsTableGUI
 *
 * @package srag\Plugins\HelpMe\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectsTableGUI extends TableGUI
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const LANG_MODULE = ProjectsConfigGUI::LANG_MODULE;


    /**
     * ProjectsTableGUI constructor
     *
     * @param ProjectsConfigGUI $parent
     * @param string            $parent_cmd
     */
    public function __construct(ProjectsConfigGUI $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritdoc
     *
     * @param Project $project
     */
    protected function getColumnValue(/*string*/
        $column, /*Project*/
        $project, /*int*/
        $format = self::DEFAULT_FORMAT
    ) : string {
        switch ($column) {
            case "support_link":
                $support_link = self::helpMe()->support()->getLink($project->getProjectUrlKey());

                $column = self::output()->getHTML(self::dic()->ui()->factory()->link()->standard($support_link, $support_link)
                    ->withOpenInNewViewport(true));
                break;

            default:
                $column = Items::getter($project, $column);
                break;
        }

        return strval($column);
    }


    /**
     * @inheritdoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "project_key"  => [
                "id"      => "project_key",
                "default" => true,
                "sort"    => true,
                "txt"     => $this->txt("key")
            ],
            "project_name" => [
                "id"      => "project_name",
                "default" => true,
                "sort"    => true,
                "txt"     => $this->txt("name")
            ],
            "support_link" => [
                "id"      => "support_link",
                "default" => true,
                "sort"    => false
            ]
        ];

        return $columns;
    }


    /**
     * @inheritdoc
     */
    protected function initColumns()/*: void*/
    {
        parent::initColumns();

        $this->addColumn($this->txt("actions"));

        $this->setDefaultOrderField("project_name");
        $this->setDefaultOrderDirection("asc");
    }


    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {
        self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_project"), self::dic()->ctrl()
            ->getLinkTargetByClass(ProjectConfigGUI::class, ProjectConfigGUI::CMD_ADD_PROJECT)));
    }


    /**
     * @inheritdoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setData(self::helpMe()->projects()->getProjects());
    }


    /**
     * @inheritdoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [];
    }


    /**
     * @inheritdoc
     */
    protected function initId()/*: void*/
    {
        $this->setId("srsu_projects");
    }


    /**
     * @inheritdoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("projects"));
    }


    /**
     * @param Project $project
     */
    protected function fillRow(/*Project*/
        $project
    )/*: void*/
    {
        self::dic()->ctrl()->setParameterByClass(ProjectConfigGUI::class, ProjectConfigGUI::GET_PARAM_PROJECT_ID, $project->getProjectId());

        parent::fillRow($project);

        $this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
            self::dic()->ui()->factory()->link()->standard($this->txt("edit_project"), self::dic()->ctrl()
                ->getLinkTargetByClass(ProjectConfigGUI::class, ProjectConfigGUI::CMD_EDIT_PROJECT)),
            self::dic()->ui()->factory()->link()->standard($this->txt("remove_project"), self::dic()->ctrl()
                ->getLinkTargetByClass(ProjectConfigGUI::class, ProjectConfigGUI::CMD_REMOVE_PROJECT_CONFIRM))
        ])->withLabel($this->txt("actions"))));
    }
}
