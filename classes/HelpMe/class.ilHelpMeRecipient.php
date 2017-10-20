<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipientSendMail.php";

/**
 * Support recipient
 */
abstract class ilHelpMeRecipient {

	/**
	 * @var ilHelpMeSupport
	 */
	protected $support;
	/**
	 * @var ilHelpMeConfig
	 */
	protected $config;
	/**
	 * @var ilHelpMeUIHookGUI
	 */
	protected $pl;


	/**
	 * @param string          $recipient
	 * @param ilHelpMeSupport $support
	 * @param ilHelpMeConfig  $config
	 *
	 * @return ilHelpMeRecipient|null
	 */
	static function getRecipient($recipient, $support, $config) {
		switch ($recipient) {
			case "send_email":
				return new ilHelpMeRecipientSendMail($support, $config);
				break;

			default:
				return NULL;
				break;
		}
	}


	/**
	 * @param ilHelpMeSupport $support
	 * @param ilHelpMeConfig  $config
	 */
	protected function __construct($support, $config) {
		$this->support = $support;
		$this->config = $config;

		$this->pl = ilHelpMePlugin::getInstance();
	}


	/**
	 * @return bool
	 */
	abstract function sendSupport();


	/**
	 * @return ilHelpMeSupport
	 */
	public function getSupport() {
		return $this->support;
	}


	/**
	 * @param ilHelpMeSupport $support
	 */
	public function setSupport($support) {
		$this->support = $support;
	}


	/**
	 * @return ilHelpMeConfig
	 */
	public function getConfig() {
		return $this->config;
	}


	/**
	 * @param ilHelpMeConfig $config
	 */
	public function setConfig($config) {
		$this->config = $config;
	}
}
