<?php

namespace srag\Plugins\HelpMe\Project;

use ilCheckboxInputGUI;
use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\MultiLineInputGUI\MultiLineInputGUI;
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
    const LANG_MODULE = ilHelpMeConfigGUI::LANG_MODULE_CONFIG;
    /**
     * @var Project
     */
    protected $object;


    /**
     * ProjectFormGUI constructor
     *
     * @param ilHelpMeConfigGUI $parent
     * @param Project           $object
     */
    public function __construct(ilHelpMeConfigGUI $parent, Project $object)
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
    protected function initAction()/*: void*/
    {
        if (!empty($this->object->getProjectId())) {
            self::dic()->ctrl()->setParameter($this->parent, "srsu_project_id", $this->object->getProjectId());
        }

        parent::initAction();

        self::dic()->ctrl()->setParameter($this->parent, "srsu_project_id", null);
    }


    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {
        if (!empty($this->object->getProjectId())) {
            $this->addCommandButton(ilHelpMeConfigGUI::CMD_UPDATE_PROJECT, $this->txt("save"));
        } else {
            $this->addCommandButton(ilHelpMeConfigGUI::CMD_CREATE_PROJECT, $this->txt("add"));
        }

        $this->addCommandButton($this->parent->getCmdForTab(ilHelpMeConfigGUI::TAB_PROJECTS), $this->txt("cancel"));
    }


    /**
     * @inheritdoc
     */
    protected function initFields()/*: void*/
    {
        self::tickets()->showUsageConfigHint();

        $this->fields = [
            "project_key"          => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            "project_url_key"      => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            "project_name"         => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            "project_issue_types"  => [
                self::PROPERTY_CLASS    => MultiLineInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_MULTI    => true,
                "setShowLabel"          => true,
                self::PROPERTY_SUBITEMS => [
                    "issue_type"  => [
                        self::PROPERTY_CLASS    => ilTextInputGUI::class,
                        self::PROPERTY_REQUIRED => true,
                        "setTitle"              => $this->txt("project_issue_type")
                    ],
                    "fix_version" => [
                        self::PROPERTY_CLASS    => ilTextInputGUI::class,
                        self::PROPERTY_REQUIRED => false,
                        "setTitle"              => $this->txt("project_fix_version")
                    ]
                ],
                "setInfo"               => self::output()->getHTML(self::dic()->ui()->factory()->listing()->descriptive([
                    $this->txt("project_issue_type")  => $this->txt("project_issue_type_info"),
                    $this->txt("project_fix_version") => $this->txt("project_fix_version_info")
                ]))
            ],
            "project_show_tickets" => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
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
