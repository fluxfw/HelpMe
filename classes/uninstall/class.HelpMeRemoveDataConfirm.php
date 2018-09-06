<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\HelpMe\Config\HelpMeConfig;
use srag\RemovePluginDataConfirm\AbstractRemovePluginDataConfirm;

/**
 * Class HelpMeRemoveDataConfirm
 *
 * @ilCtrl_isCalledBy HelpMeRemoveDataConfirm: ilUIPluginRouterGUI
 */
class HelpMeRemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	public function getUninstallRemovesData()/*: ?bool*/ {
		return HelpMeConfig::getUninstallRemovesData();
	}


	/**
	 * @inheritdoc
	 */
	public function setUninstallRemovesData(/*bool*/
		$uninstall_removes_data)/*: void*/ {
		HelpMeConfig::setUninstallRemovesData($uninstall_removes_data);
	}


	/**
	 * @inheritdoc
	 */
	public function removeUninstallRemovesData()/*: void*/ {
		HelpMeConfig::removeUninstallRemovesData();
	}
}
