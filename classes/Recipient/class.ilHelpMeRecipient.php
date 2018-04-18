<?php

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
	 * @var ilHelpMePlugin
	 */
	protected $pl;


	/**
	 * @param string          $recipient
	 * @param ilHelpMeSupport $support
	 * @param ilHelpMeConfig  $config
	 *
	 * @return ilHelpMeRecipient|null
	 */
	public static function getRecipient($recipient, ilHelpMeSupport $support, ilHelpMeConfig $config) {
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
	protected function __construct(ilHelpMeSupport $support, ilHelpMeConfig $config) {
		$this->support = $support;
		$this->config = $config;

		$this->pl = ilHelpMePlugin::getInstance();
	}


	/**
	 * Send support to recipient
	 *
	 * @return bool
	 */
	public abstract function sendSupportToRecipient();


	/**
	 * Send confirmation email
	 *
	 * @return bool
	 */
	public function sendConfirmationMail() {
		try {
			$mailer = new ilMimeMail();

			if (ILIAS_VERSION_NUMERIC >= "5.3") {
				global $DIC;

				/** @var ilMailMimeSenderFactory $senderFactory */
				$senderFactory = $DIC["mail.mime.sender.factory"];

				$mailer->From($senderFactory->system());
			}

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
