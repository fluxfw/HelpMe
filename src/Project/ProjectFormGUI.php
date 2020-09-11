<?php

namespace srag\Plugins\HelpMe\Project;

use ilCheckboxInputGUI;
use ilHelpMePlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\MultiLineNewInputGUI\MultiLineNewInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectFormGUI
 *
 * @package srag\Plugins\HelpMe\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectFormGUI extends PropertyFormGUI
{

    use HelpMeTrait;

    const LANG_MODULE = ProjectsConfigGUI::LANG_MODULE;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var Project
     */
    protected $project;


    /**
     * ProjectFormGUI constructor
     *
     * @param ProjectConfigGUI $parent
     * @param Project          $project
     */
    public function __construct(ProjectConfigGUI $parent, Project $project)
    {
        $this->project = $project;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (!parent::storeForm()) {
            return false;
        }

        self::helpMe()->projects()->storeProject($this->project);

        return true;
    }


    /**
     * @inheritDoc
     */
    protected function getValue(string $key)
    {
        switch ($key) {
            default:
                return Items::getter($this->project, $key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands() : void
    {
        if (!empty($this->project->getProjectId())) {
            $this->addCommandButton(ProjectConfigGUI::CMD_UPDATE_PROJECT, $this->txt("save"));
        } else {
            $this->addCommandButton(ProjectConfigGUI::CMD_CREATE_PROJECT, $this->txt("add"));
        }

        $this->addCommandButton(ProjectConfigGUI::CMD_BACK, $this->txt("cancel"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields() : void
    {
        $this->fields = [
            "project_key"          => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                "setTitle"              => $this->txt("key")
            ],
            "project_url_key"      => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                "setTitle"              => $this->txt("url_key")
            ],
            "project_name"         => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                "setTitle"              => $this->txt("name")
            ],
            "project_issue_types"  => [
                self::PROPERTY_CLASS    => MultiLineNewInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_SUBITEMS => [
                    "issue_type"  => [
                        self::PROPERTY_CLASS    => ilTextInputGUI::class,
                        self::PROPERTY_REQUIRED => true
                    ],
                    "fix_version" => [
                        self::PROPERTY_CLASS    => ilTextInputGUI::class,
                        self::PROPERTY_REQUIRED => false
                    ]
                ],
                "setTitle"              => $this->txt("issue_types"),
                "setInfo"               => self::output()->getHTML(self::dic()->ui()->factory()->listing()->descriptive([
                    $this->txt("issue_type")  => $this->txt("issue_type_info"),
                    $this->txt("fix_version") => $this->txt("fix_version_info")
                ]))
            ],
            "project_show_tickets" => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
                "setTitle"           => $this->txt("show_tickets")
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId() : void
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle() : void
    {
        $this->setTitle($this->txt(!empty($this->project->getProjectId()) ? "edit_project" : "add_project"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(string $key, $value) : void
    {
        switch ($key) {
            case "project_issue_types":
                Items::setter($this->project, $key, array_values($value));
                break;

            default:
                Items::setter($this->project, $key, $value);
                break;
        }
    }
}
