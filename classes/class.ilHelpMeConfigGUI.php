<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\ActiveRecordConfigGUI;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;

/**
 * Class ilHelpMeConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilHelpMeConfigGUI extends ActiveRecordConfigGUI {

	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CONFIG_FORM_GUI_CLASS_NAME = ConfigFormGUI::class;
	const LANG_MODULE_CONFIG = "config";
}
