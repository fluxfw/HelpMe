<?php
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * HelpMe Plugin
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin {

	use \srag\DICTrait;
	const PLUGIN_ID = "srsu";
	const PLUGIN_NAME = "HelpMe";
	/**
	 * @var ilHelpMePlugin
	 */
	protected static $instance = NULL;


	/**
	 * @return ilHelpMePlugin
	 */
	public static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return bool
	 */
	protected function beforeUninstall() {
		$this->ilDB->dropTable(ilHelpMeConfig::TABLE_NAME, false);
		$this->ilDB->dropTable(ilHelpMeConfigPriority::TABLE_NAME, false);
		$this->ilDB->dropTable(ilHelpMeConfigRole::TABLE_NAME, false);

		return true;
	}
}
