<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\Plugins\HelpMe\Config\HelpMeConfig;
use srag\Plugins\HelpMe\Config\HelpMeConfigOld;
use srag\Plugins\HelpMe\Config\HelpMeConfigPriority;
use srag\Plugins\HelpMe\Config\HelpMeConfigRole;
use srag\RemovePluginDataConfirm\PluginUninstallTrait;

/**
 * Class ilHelpMePlugin
 *
 * @author studer + raimann ag <support-custom1@studer-raimann.ch>
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin {

	use PluginUninstallTrait;
	const PLUGIN_ID = "srsu";
	const PLUGIN_NAME = "HelpMe";
	const PLUGIN_CLASS_NAME = self::class;
	const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = HelpMeRemoveDataConfirm::class;
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
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(HelpMeConfigOld::TABLE_NAME, false);
		self::dic()->database()->dropTable(HelpMeConfig::TABLE_NAME, false);
		self::dic()->database()->dropTable(HelpMeConfigPriority::TABLE_NAME, false);
		self::dic()->database()->dropTable(HelpMeConfigRole::TABLE_NAME, false);
	}
}
