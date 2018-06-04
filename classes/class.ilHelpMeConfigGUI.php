<?php
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * HelpMe Config GUI
 */
class ilHelpMeConfigGUI extends ilPluginConfigGUI {

	use \srag\DICTrait;
	const CMD_CONFIGURE = "configure";
	const CMD_UPDATE_CONFIGURE = "updateConfigure";
	/**
	 * @var ilHelpMePlugin
	 */
	protected $pl;


	/**
	 *
	 */
	public function __construct() {
		$this->pl = ilHelpMePlugin::getInstance();
	}


	/**
	 *
	 * @param string $cmd
	 */
	public function performCommand($cmd) {
		$next_class = $this->ilCtrl->getNextClass($this);

		switch ($next_class) {
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
	 * @return ilHelpMeConfigFormGUI
	 */
	protected function getConfigurationForm() {
		$form = new ilHelpMeConfigFormGUI($this);

		return $form;
	}


	/**
	 * @param string $html
	 */
	protected function show($html) {
		$this->tpl->setContent($html);
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

		ilUtil::sendSuccess($this->txt("srsu_configuration_saved"));

		$this->show($form->getHTML());
	}


	/**
	 * @param string $a_var
	 *
	 * @return string
	 */
	protected function txt($a_var) {
		return $this->pl->txt($a_var);
	}
}
