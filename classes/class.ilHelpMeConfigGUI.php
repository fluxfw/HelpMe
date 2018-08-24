<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\HelpMeConfigFormGUI;

/**
 * Class ilHelpMeConfigGUI
 */
class ilHelpMeConfigGUI extends ilPluginConfigGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CMD_CONFIGURE = "configure";
	const CMD_UPDATE_CONFIGURE = "updateConfigure";


	/**
	 * ilHelpMeConfigGUI constructor
	 */
	public function __construct() {

	}


	/**
	 *
	 * @param string $cmd
	 */
	public function performCommand($cmd) {
		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch (strtolower($next_class)) {
			default:
				switch ($cmd) {
					case self::CMD_CONFIGURE:
					case self::CMD_UPDATE_CONFIGURE:
						$this->$cmd();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 * @return HelpMeConfigFormGUI
	 */
	protected function getConfigurationForm(): HelpMeConfigFormGUI {
		$form = new HelpMeConfigFormGUI($this);

		return $form;
	}


	/**
	 * @param string $html
	 */
	protected function show(string $html) {
		self::output($html);
	}


	/**
	 *
	 */
	protected function configure() {
		$form = $this->getConfigurationForm();

		$this->show($form->getHTML());
	}


	/**
	 *
	 */
	protected function updateConfigure() {
		$form = $this->getConfigurationForm();
		$form->setValuesByPost();

		if (!$form->checkInput()) {
			$this->show($form->getHTML());

			return;
		}

		$form->updateConfig();

		ilUtil::sendSuccess(self::translate("srsu_configuration_saved"));

		$this->show($form->getHTML());
	}
}
