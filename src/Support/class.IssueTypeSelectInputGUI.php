<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ilSelectInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class IssueTypeSelectInputGUI
 *
 * @package           srag\Plugins\HelpMe\Support
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Support\IssueTypeSelectInputGUI: srag\Plugins\HelpMe\Support\SupportGUI
 */
class IssueTypeSelectInputGUI extends ilSelectInputGUI
{

    use DICTrait;
    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const CMD_GET_ISSUE_TYPES_OF_PROJECT = "getIssueTypesOfProject";
    /**
     * @var SupportFormGUI
     */
    public $parent_gui;


    /**
     * @param string $a_mode
     *
     * @return string
     */
    public function render(/*string*/
        $a_mode = ""
    ) : string {
        $this->setIssueTypesOptions($this->parent_gui->getProject());

        return parent::render($a_mode);
    }


    /**
     * @return bool
     */
    public function checkInput() : bool
    {
        $project_select = $this->parent_gui->extractProjectSelector();

        // First validate project
        if ($project_select->checkInput()) {

            // Then set project issue types
            $project = self::helpMe()->project()->getProjectByUrlKey(Items::getValueFromItem($project_select));

            $this->parent_gui->setProject($project);

            $this->setIssueTypesOptions($project);
        }

        // This will validate issue type options
        return parent::checkInput();
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_GET_ISSUE_TYPES_OF_PROJECT:
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
    protected function getIssueTypesOfProject()/*: void*/
    {
        $project_url_key = filter_input(INPUT_GET, "project_url_key");

        $project = self::helpMe()->project()->getProjectByUrlKey($project_url_key);

        $this->parent_gui->setProject($project);

        self::output()->output($this);
    }


    /**
     * @param Project|null $project
     */
    protected function setIssueTypesOptions(/*?*/
        Project $project = null
    )/*: void*/
    {
        $options = [
            "" => "&lt;" . $this->parent_gui->txt("please_select") . "&gt;"
        ];

        if ($project !== null) {
            $options += self::helpMe()->project()->getIssueTypesOptions($project);

            $this->setDisabled(false);
        } else {

            $this->setDisabled(true);
        }

        $this->setOptions($options);
    }
}
