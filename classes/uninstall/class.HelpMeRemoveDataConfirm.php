<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RemovePluginDataConfirm\HelpMe\AbstractRemovePluginDataConfirm;

/**
 * Class HelpMeRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy HelpMeRemoveDataConfirm: ilUIPluginRouterGUI
 */
class HelpMeRemoveDataConfirm extends AbstractRemovePluginDataConfirm
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
}
