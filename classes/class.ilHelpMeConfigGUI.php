<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfigGUI;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Notification\Ctrl\Notifications4PluginCtrl;
use srag\Plugins\HelpMe\Project\ProjectsConfigGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ilHelpMeConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilHelpMeConfigGUI extends ActiveRecordConfigGUI
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var array
     */
    protected static $tabs
        = [
            self::TAB_CONFIGURATION                     => ConfigFormGUI::class,
            ProjectsConfigGUI::TAB_PROJECTS             => [
                ProjectsConfigGUI::class,
                ProjectsConfigGUI::CMD_LIST_PROJECTS
            ],
            Notifications4PluginCtrl::TAB_NOTIFICATIONS => [
                Notifications4PluginCtrl::class,
                Notifications4PluginCtrl::CMD_LIST_NOTIFICATIONS
            ]
        ];
}
