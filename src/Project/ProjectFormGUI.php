<?php

namespace srag\Plugins\HelpMe\Project;

use ilCheckboxInputGUI;
use ilHelpMePlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\MultiLineNewInputGUI\MultiLineNewInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\ObjectPropertyFormGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectFormGUI
 *
 * @package srag\Plugins\HelpMe\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectFormGUI extends ObjectPropertyFormGUI
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const LANG_MODULE = ProjectsConfigGUI::LANG_MODULE;
    /**
     * @var Project
     */
    protected $object;


    /**
     * ProjectFormGUI constructor
     *
     * @param ProjectConfigGUI $parent
     * @param Project          $object
     */
    public function __construct(ProjectConfigGUI $parent, Project $object)
    {
        parent::__construct($parent, $object);
    }


    /**
     * @inheritdoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            default:
                return parent::getValue($key);
        }
    }


    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {
        if (!empty($this->object->getProjectId())) {
            $this->addCommandButton(ProjectConfigGUI::CMD_UPDATE_PROJECT, $this->txt("save"));
        } else {
            $this->addCommandButton(ProjectConfigGUI::CMD_CREATE_PROJECT, $this->txt("add"));
        }

        $this->addCommandButton(ProjectConfigGUI::CMD_BACK, $this->txt("cancel"));
    }


    /**
     * @inheritdoc
     */
    protected function initFields()/*: void*/
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
     * @inheritdoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritdoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt(!empty($this->object->getProjectId()) ? "edit_project" : "add_project"));
    }


    /**
     * @inheritdoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            case "project_issue_types":
                parent::storeValue($key, array_values($value));
                break;

            default:
                parent::storeValue($key, $value);
                break;
        }
    }
}
