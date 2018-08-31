<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\HelpMe\Config\HelpMeConfig;
use srag\RemovePluginDataConfirm\AbstractRemovePluginDataConfirm;

/**
 * Class HelpMeRemoveDataConfirm
 *
 * @ilCtrl_isCalledBy HelpMeRemoveDataConfirm: ilUIPluginRouterGUI
 */
class HelpMeRemoveDataConfirm extends AbstractRemovePluginDataConfirm  {

	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	public function removeUninstallRemovesData() {
		HelpMeConfig::removeUninstallRemovesData();
	}


	/**
	 * @inheritdoc
	 */
	public function getUninstallRemovesData() {
		return HelpMeConfig::getUninstallRemovesData();
	}


	/**
	 * @inheritdoc
	 */
	public function setUninstallRemovesData($uninstall_removes_data) {
		HelpMeConfig::setUninstallRemovesData($uninstall_removes_data);
	}
}
