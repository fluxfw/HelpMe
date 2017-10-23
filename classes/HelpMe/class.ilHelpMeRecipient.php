<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipientSendMail.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipientCreateJiraTicket.php";
require_once "Services/Mail/classes/class.ilMimeMail.php";

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

			case "create_jira_ticket":
				return new ilHelpMeRecipientCreateJiraTicket($support, $config);
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
	 * Send support to recipient
	 *
	 * @return bool
	 */
	abstract function sendSupportToRecipient();


	/**
	 * Send confirmation email
	 *
	 * @return bool
	 */
	function sendConfirmationMail() {
		$mailer = new ilMimeMail();

		$mailer->To($this->support->getEmail());

		$mailer->Subject($this->pl->txt("srsu_confirmation") . ": " . $this->support->getSubject());

		$mailer->Body($this->support->getBody());

		foreach ($this->support->getScreenshots() as $screenshot) {
			$mailer->Attach($screenshot["tmp_name"], $screenshot["type"], "attachment", $screenshot["name"]);
		}

		$mailer->Send();

		return true; // TODO: check error
	}


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
