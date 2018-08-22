<?php
require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;

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
		self::dic()->database()->dropTable(ilHelpMeConfigOld::TABLE_NAME, false);
		self::dic()->database()->dropTable(ilHelpMeConfig::TABLE_NAME, false);
		self::dic()->database()->dropTable(ilHelpMeConfigPriority::TABLE_NAME, false);
		self::dic()->database()->dropTable(ilHelpMeConfigRole::TABLE_NAME, false);

		return true;
	}
}
