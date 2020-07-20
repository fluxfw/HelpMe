<?php

use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Ticket\TicketsGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ilHelpMeUIHookGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilHelpMeUIHookGUI extends ilUIHookPluginGUI
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const SESSION_PROJECT_URL_KEY = ilHelpMePlugin::PLUGIN_ID . "_project_url_key";


    /**
     * ilHelpMeUIHookGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function gotoHook() : void
    {
        $target = filter_input(INPUT_GET, "target");

        $matches = [];
        preg_match("/^uihk_" . ilHelpMePlugin::PLUGIN_ID . "(_(.*))?/uim", $target, $matches);

        if (is_array($matches) && count($matches) >= 1) {
            $project_url_key = $matches[2];

            if ($project_url_key === null) {
                $project_url_key = "";
            }

            if (strpos($project_url_key, "tickets") === 0) {
                // Tickets
                $project_url_key = substr($project_url_key, strlen("tickets"));
                if ($project_url_key[0] === "_") {
                    $project_url_key = substr($project_url_key, 1);
                }

                self::dic()->ctrl()->setTargetScript("ilias.php"); // Fix ILIAS 5.3 bug
                self::dic()->ctrl()->initBaseClass(ilUIPluginRouterGUI::class); // Fix ILIAS bug

                self::dic()->ctrl()->setParameterByClass(TicketsGUI::class, "project_url_key", $project_url_key);

                self::dic()->ctrl()->redirectByClass([ilUIPluginRouterGUI::class, TicketsGUI::class], TicketsGUI::CMD_SET_PROJECT_FILTER);
            } else {
                // Support
                ilSession::set(self::SESSION_PROJECT_URL_KEY, $project_url_key);

                self::dic()->ctrl()->redirectToURL("/");
            }
        }
    }
}
