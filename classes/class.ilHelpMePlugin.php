<?php

require_once "Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php";

/**
 * HelpMe Plugin
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin {

	/**
	 * @var ilHelpMePlugin
	 */
	protected static $cache;


	/**
	 * @return ilHelpMePlugin
	 */
	static function getInstance() {
		if (!isset(self::$cache)) {
			self::$cache = new self();
		}

		return self::$cache;
	}


	function getPluginName() {
		return "HelpMe";
	}
}
