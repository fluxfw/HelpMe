<?php

namespace srag\Plugins\HelpMe\Project;

use ilHelpMePlugin;
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
    const LANG_MODULE = ProjectsConfigGUI::LANG_MODULE_PROJECTS;


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
     */
    protected function getColumnValue(/*string*/
        $column, /*array*/
        $row, /*int*/
        $format = self::DEFAULT_FORMAT
    ) : string {
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
            ->getLinkTarget($this->parent_obj, ProjectsConfigGUI::CMD_ADD_PROJECT)));
    }


    /**
     * @inheritdoc
     */
    protected function initData()/*: void*/
    {
        $projects = self::projects()->getProjectsArray();

        $this->setData(array_map(function (array $project) : array {
            $support_link = self::supports()->getLink($project["project_url_key"]);

            $project["support_link"] = self::output()->getHTML(self::dic()->ui()->factory()->link()->standard($support_link, $support_link)
                ->withOpenInNewViewport(true));

            return $project;
        }, $projects));
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
     * @param array $row
     */
    protected function fillRow(/*array*/
        $row
    )/*: void*/
    {
        self::dic()->ctrl()->setParameter($this->parent_obj, "srsu_project_id", $row["project_id"]);

        parent::fillRow($row);

        $this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
            self::dic()->ui()->factory()->button()->shy($this->txt("edit_project"), self::dic()->ctrl()
                ->getLinkTarget($this->parent_obj, ProjectsConfigGUI::CMD_EDIT_PROJECT)),
            self::dic()->ui()->factory()->button()->shy($this->txt("remove_project"), self::dic()->ctrl()
                ->getLinkTarget($this->parent_obj, ProjectsConfigGUI::CMD_REMOVE_PROJECT_CONFIRM))
        ])->withLabel($this->txt("actions"))));
    }
}
