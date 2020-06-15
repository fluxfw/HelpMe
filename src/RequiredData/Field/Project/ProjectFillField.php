<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Project;

use ilHelpMePlugin;
use ILIAS\UI\Component\Input\Field\Input;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\Plugins\HelpMe\RequiredData\Field\Project\Form\ProjectSelectInputGUI;
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


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        $input = (new InputGUIWrapperUIInputComponent(new ProjectSelectInputGUI($this->field->getLabel())))->withByline($this->field->getDescription())
            ->withRequired($this->field->isRequired());

        $input->getInput()->setOptions(($this->field->isRequired() && count(self::helpMe()->projects()->getProjectsOptions()) === 1
                ? []
                : [
                    "" => "&lt;" . self::requiredData()
                            ->getPlugin()
                            ->translate("please_select", SupportGUI::LANG_MODULE) . "&gt;"
                ]) + self::helpMe()->projects()->getProjectsOptions());

        $project = null;

        // Preselect project (Support link)
        $project_url_key = strval(filter_input(INPUT_GET, SupportGUI::GET_PARAM_PROJECT_URL_KEY));
        if (!empty($project_url_key)) {
            $project = self::helpMe()->projects()->getProjectByUrlKey($project_url_key);
        }

        if ($project === null && $this->field->isRequired() && count(self::helpMe()->projects()->getProjectsOptions()) === 1) {
            $project = current(self::helpMe()->projects()->getProjects());
            if ($project === false) {
                $project = null;
            }
        }

        if ($project !== null) {
            $input->getInput()->setProject($project);
            $input->getInput()->setValue($project->getProjectUrlKey());
        }

        return $input;
    }
}
