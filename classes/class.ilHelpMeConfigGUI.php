<?php

require_once "Services/Component/classes/class.ilPluginConfigGUI.php";

/**
 *
 */
class ilHelpMeConfigGUI extends ilPluginConfigGUI {

	/**
	 *
	 * @param string $cmd
	 */
	function performCommand($cmd) {
		switch ($cmd) {
			case "configure":
				$this->$cmd();
				break;

			default:
				break;
		}
	}


	/**
	 *
	 */
	function configure() {

	}
}
