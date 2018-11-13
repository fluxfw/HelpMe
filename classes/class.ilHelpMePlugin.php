<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Config\ConfigOld;
use srag\Plugins\HelpMe\Config\ConfigPriorityOld;
use srag\Plugins\HelpMe\Config\ConfigRoleOld;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RemovePluginDataConfirm\HelpMe\PluginUninstallTrait;

/**
 * Class ilHelpMePlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin {

	use PluginUninstallTrait;
	use HelpMeTrait;
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
		self::dic()->database()->dropTable(ConfigOld::TABLE_NAME, false);
		self::dic()->database()->dropTable(Config::TABLE_NAME, false);
		self::dic()->database()->dropTable(ConfigPriorityOld::TABLE_NAME, false);
		self::dic()->database()->dropTable(ConfigRoleOld::TABLE_NAME, false);
	}
}
