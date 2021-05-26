<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form;

require_once __DIR__ . "/../../../../../vendor/autoload.php";

use ilHelpMePlugin;
use ilSelectInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\Support\Form\SupportFormBuilder;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class IssueTypeSelectInputGUI
 *
 * @package           srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form\IssueTypeSelectInputGUI: srag\Plugins\HelpMe\Support\SupportGUI
 */
class IssueTypeSelectInputGUI extends ilSelectInputGUI
{

    use DICTrait;
    use HelpMeTrait;

    const CMD_GET_ISSUE_TYPES_OF_PROJECT = "getIssueTypesOfProject";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


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
    public function checkInput() : bool
    {
        $project_select = SupportFormBuilder::getFormParent()->extractProjectSelector();

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
    public function executeCommand() : void
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
     * @inheritDoc
     */
    public function render(/*string*/ $a_mode = "") : string
    {
        $project_select = SupportFormBuilder::getFormParent()->extractProjectSelector();

        if ($project_select !== null) {
            $this->setIssueTypesOptions($project_select->getProject());
        }

        return self::output()->getHTML([
            '<div class="form_helpme_form_issuetypefield">',
            parent::render($a_mode) .
            '</div>'
        ]);
    }


    /**
     *
     */
    protected function getIssueTypesOfProject() : void
    {
        $project_url_key = filter_input(INPUT_GET, "project_url_key");

        $project = self::helpMe()->projects()->getProjectByUrlKey($project_url_key);

        $project_select = SupportFormBuilder::getFormParent()->extractProjectSelector();
        if ($project_select !== null) {
            $project_select->setProject($project);
        }

        self::output()->output($this);
    }


    /**
     * @param Project|null $project
     */
    protected function setIssueTypesOptions(?Project $project = null) : void
    {
        $options = [
            "" => "&lt;" . self::plugin()->translate("please_select", SupportGUI::LANG_MODULE) . "&gt;"
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
