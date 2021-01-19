<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Project\Form;

use ilHelpMePlugin;
use ilSelectInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectSelectInputGUI
 *
 * @package           srag\Plugins\HelpMe\RequiredData\Field\Project\Form
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\RequiredData\Field\Project\Form\ProjectSelectInputGUI: srag\Plugins\HelpMe\Support\SupportGUI
 */
class ProjectSelectInputGUI extends ilSelectInputGUI
{

    use DICTrait;
    use HelpMeTrait;

    const CMD_GET_SHOW_TICKETS_LINK_OF_PROJECT = "getShowTicketsLinkOfProject";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var Project|null
     */
    protected $project = null;


    /**
     * ProjectSelectInputGUI constructor
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
        $this->setProject(self::helpMe()->projects()->getProjectByUrlKey($this->getValue()));

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
                    case self::CMD_GET_SHOW_TICKETS_LINK_OF_PROJECT:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @return Project|null
     */
    public function getProject() : ?Project
    {
        return $this->project;
    }


    /**
     * @param Project|null $project
     */
    public function setProject(?Project $project = null) : void
    {
        $this->project = $project;
    }


    /**
     * @inheritDoc
     */
    public function render(/*string*/ $a_mode = "") : string
    {
        if (self::helpMe()->tickets()->isEnabled()) {

            $tpl = self::plugin()->template("project_select_input.html");

            $tpl->setVariable("SELECT", parent::render($a_mode));

            $tpl->setVariable("SHOW_TICKETS_LINK", $this->getShowTicketsLink($this->project));

            return self::output()->getHTML($tpl);
        }

        return self::output()->getHTML([
            '<div class="form_helpme_form_projectfield">',
            parent::render($a_mode) .
            '</div>'
        ]);
    }


    /**
     * @param Project|null $project
     *
     * @return string
     */
    protected function getShowTicketsLink(?Project $project = null) : string
    {
        if (self::helpMe()->tickets()->isEnabled() && $project !== null && $project->isProjectShowTickets()) {

            return self::output()->getHTML(self::dic()->ui()->factory()->link()->standard(self::plugin()
                ->translate("show_tickets_of_selected_project", SupportGUI::LANG_MODULE), self::helpMe()->tickets()->getLink($project->getProjectUrlKey()))
                ->withOpenInNewViewport(true));
        }

        return "";
    }


    /**
     *
     */
    protected function getShowTicketsLinkOfProject() : void
    {
        $project_url_key = filter_input(INPUT_GET, "project_url_key");

        $project = self::helpMe()->projects()->getProjectByUrlKey($project_url_key);

        self::output()->output($this->getShowTicketsLink($project));
    }
}
