<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipientSendMail.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipientCreateJiraTicket.php";
require_once "Services/Mail/classes/class.ilMimeMail.php";

/**
 * Support recipient
 */
abstract class ilHelpMeRecipient {

	const SEND_EMAIL = "send_email";
	const CREATE_JIRA_TICKET = "create_jira_ticket";
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
			case self::SEND_EMAIL:
				return new ilHelpMeRecipientSendMail($support, $config);
				break;

			case self::CREATE_JIRA_TICKET:
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
		try {
			$mailer = new ilMimeMail();

			$mailer->To($this->support->getEmail());

			$mailer->Subject($this->pl->txt("srsu_confirmation") . ": " . $this->support->getSubject());

			$mailer->Body($this->support->getBody("email"));

			foreach ($this->support->getScreenshots() as $screenshot) {
				$mailer->Attach($screenshot["tmp_name"], $screenshot["type"], "attachment", $screenshot["name"]);
			}

			$mailer->Send();

			return true;
		} catch (Exception $ex) {
			return false;
		}
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
