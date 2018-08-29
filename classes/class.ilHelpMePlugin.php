<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\HelpMeConfig;
use srag\Plugins\HelpMe\Config\HelpMeConfigOld;
use srag\Plugins\HelpMe\Config\HelpMeConfigPriority;
use srag\Plugins\HelpMe\Config\HelpMeConfigRole;

/**
 * Class ilHelpMePlugin
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin {

	use DICTrait;
	const PLUGIN_ID = "srsu";
	const PLUGIN_NAME = "HelpMe";
	const PLUGIN_CLASS_NAME = self::class;
	/**
	 * @var self|null
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * ilHelpMePlugin constructor
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * @return string
	 */
	public function getPluginName(): string {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return bool
	 */
	protected function beforeUninstall(): bool {
		$uninstall_remove_data = HelpMeConfig::getUninstallRemoveData();

		if ($uninstall_remove_data === NULL) {
			HelpMeRemoveDataConfirm::saveParameterByClass();

			self::dic()->ctrl()->redirectByClass([
				ilUIPluginRouterGUI::class,
				HelpMeRemoveDataConfirm::class
			], HelpMeRemoveDataConfirm::CMD_CONFIRM_REMOVE_DATA);

			return false;
		}

		$uninstall_remove_data = boolval($uninstall_remove_data);

		if ($uninstall_remove_data) {
			$this->removeData();
		} else {
			// Ask again if reinstalled
			HelpMeConfig::deleteUninstallRemoveData();
		}

		return true;
	}


	/**
	 *
	 */
	protected function removeData() {
		self::dic()->database()->dropTable(HelpMeConfigOld::TABLE_NAME, false);
		self::dic()->database()->dropTable(HelpMeConfig::TABLE_NAME, false);
		self::dic()->database()->dropTable(HelpMeConfigPriority::TABLE_NAME, false);
		self::dic()->database()->dropTable(HelpMeConfigRole::TABLE_NAME, false);
	}
}
