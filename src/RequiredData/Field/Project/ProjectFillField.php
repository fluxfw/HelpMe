<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Project;

use ilHelpMePlugin;
use ilHelpMeUIHookGUI;
use ilSession;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class ProjectFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectFillField extends AbstractFillField
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var ProjectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(ProjectField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getFormFields() : array
    {
        $field = [
            PropertyFormGUI::PROPERTY_CLASS    => ProjectSelectInputGUI::class,
            PropertyFormGUI::PROPERTY_REQUIRED => true,
            PropertyFormGUI::PROPERTY_OPTIONS  => ($this->field->isRequired() && count(self::helpMe()->projects()->getProjectsOptions()) === 1
                    ? []
                    : [
                        "" => "&lt;" . self::requiredData()
                                ->getPlugin()
                                ->translate("please_select", SupportGUI::LANG_MODULE) . "&gt;"
                    ]) + self::helpMe()->projects()->getProjectsOptions()
        ];

        $project = null;

        // Preselect project (Support link)
        $project_url_key = ilSession::get(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);
        if (!empty($project_url_key)) {
            ilSession::set(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY, "");
            ilSession::clear(ilHelpMeUIHookGUI::SESSION_PROJECT_URL_KEY);

            $project = self::helpMe()->projects()->getProjectByUrlKey($project_url_key);
        }

        if ($project === null && $this->field->isRequired() && count(self::helpMe()->projects()->getProjectsOptions()) === 1) {
            $project = current(self::helpMe()->projects()->getProjects());
            if ($project === false) {
                $project = null;
            }
        }

        if ($project !== null) {
            $field["setProject"] = $project;
            $field[PropertyFormGUI::PROPERTY_VALUE] = $project->getProjectUrlKey();
        }

        return $field;
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return strval($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        $project = self::helpMe()->projects()->getProjectByUrlKey($fill_value);

        if ($project !== null) {
            return htmlspecialchars($project->getProjectName());
        } else {
            return "";
        }
    }
}
