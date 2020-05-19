<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form;

use ilHelpMePlugin;
use ilSelectInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\Support\SupportFormGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class IssueTypeSelectInputGUI
 *
 * @package           srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form\IssueTypeSelectInputGUI: srag\Plugins\HelpMe\Support\SupportGUI
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
     * IssueTypeSelectInputGUI constructor
     *
     * @param string $a_title
     * @param string $a_postvar
     */
    public function __construct(string $a_title = "", string $a_postvar = "")
    {
        parent::__construct($a_title, $a_postvar);
    }


    /**
     * @inheritDoc
     */
    public function render(/*string*/ $a_mode = "") : string
    {
        $project_select = $this->parent_gui->extractProjectSelector();

        if ($project_select !== null) {
            $this->setIssueTypesOptions($project_select->getProject());
        }

        return parent::render($a_mode);
    }


    /**
     * @inheritDoc
     */
    public function checkInput() : bool
    {
        $project_select = $this->parent_gui->extractProjectSelector();

        // First validate project
        if ($project_select !== null && $project_select->checkInput()) {

            // Then set project issue types
            $this->setIssueTypesOptions($project_select->getProject());
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

        $project = self::helpMe()->projects()->getProjectByUrlKey($project_url_key);

        $project_select = $this->parent_gui->extractProjectSelector();
        if ($project_select !== null) {
            $project_select->setProject($project);
        }

        self::output()->output($this);
    }


    /**
     * @param Project|null $project
     */
    protected function setIssueTypesOptions(/*?*/ Project $project = null)/*: void*/
    {
        $options = [
            "" => "&lt;" . $this->parent_gui->txt("please_select") . "&gt;"
        ];

        if ($project !== null) {
            if ($this->getRequired() && count(self::helpMe()->projects()->getIssueTypesOptions($project)) === 1) {
                $options = [];
            }

            $options += self::helpMe()->projects()->getIssueTypesOptions($project);

            $this->setDisabled(false);
        } else {

            $this->setDisabled(true);
        }

        $this->setOptions($options);
    }
}
