<?php

namespace srag\Plugins\HelpMe\Project;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ProjectsConfigGUI
 *
 * @package           srag\Plugins\HelpMe\Project
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Project\ProjectsConfigGUI: ilHelpMeConfigGUI
 */
class ProjectsConfigGUI
{

    use DICTrait;
    use HelpMeTrait;

    const CMD_LIST_PROJECTS = "listProjects";
    const LANG_MODULE = "projects";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TAB_LIST_PROJECTS = "list_projects";


    /**
     * ProjectsConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public static function addTabs() : void
    {
        self::dic()->tabs()->addTab(self::TAB_LIST_PROJECTS, self::plugin()->translate("projects", self::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(self::class, self::CMD_LIST_PROJECTS));
    }


    /**
     *
     */
    public function executeCommand() : void
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ProjectConfigGUI::class):
                self::dic()->ctrl()->forwardCommand(new ProjectConfigGUI($this));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_LIST_PROJECTS:
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
    protected function listProjects() : void
    {
        self::dic()->tabs()->activateTab(self::TAB_LIST_PROJECTS);

        $table = self::helpMe()->projects()->factory()->newTableInstance($this);

        self::output()->output($table);
    }


    /**
     *
     */
    protected function setTabs() : void
    {

    }
}
