<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RemovePluginDataConfirm\AbstractRemovePluginDataConfirm;

/**
 * Class HelpMeRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy HelpMeRemoveDataConfirm: ilUIPluginRouterGUI
 */
class HelpMeRemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	public function getUninstallRemovesData()/*: ?bool*/ {
		return Config::getUninstallRemovesData();
	}


	/**
	 * @inheritdoc
	 */
	public function setUninstallRemovesData(/*bool*/
		$uninstall_removes_data)/*: void*/ {
		Config::setUninstallRemovesData($uninstall_removes_data);
	}


	/**
	 * @inheritdoc
	 */
	public function removeUninstallRemovesData()/*: void*/ {
		Config::removeUninstallRemovesData();
	}
}
