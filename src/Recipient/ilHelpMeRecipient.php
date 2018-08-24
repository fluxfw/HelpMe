<?php

namespace srag\Plugins\HelpMe\Recipient;

use Exception;
use ilHelpMePlugin;
use ilMimeMail;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Support\ilHelpMeSupport;

/**
 * Class ilHelpMeRecipient
 *
 * @package srag\Plugins\HelpMe\Recipient
 */
abstract class ilHelpMeRecipient {

	use DICTrait;
	const SEND_EMAIL = "send_email";
	const CREATE_JIRA_TICKET = "create_jira_ticket";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var ilHelpMeSupport
	 */
	protected $support;


	/**
	 * @param string          $recipient
	 * @param ilHelpMeSupport $support
	 *
	 * @return ilHelpMeRecipient|null
	 */
	public static function getRecipient(string $recipient, ilHelpMeSupport $support) {
		switch ($recipient) {
			case self::SEND_EMAIL:
				return new ilHelpMeRecipientSendMail($support);
				break;

			case self::CREATE_JIRA_TICKET:
				return new ilHelpMeRecipientCreateJiraTicket($support);
				break;

			default:
				return NULL;
				break;
		}
	}


	/**
	 * ilHelpMeRecipient constructor
	 *
	 * @param ilHelpMeSupport $support
	 */
	protected function __construct(ilHelpMeSupport $support) {
		$this->support = $support;
	}


	/**
	 * Send support to recipient
	 *
	 * @return bool
	 */
	public abstract function sendSupportToRecipient(): bool;


	/**
	 * Send confirmation email
	 *
	 * @return bool
	 */
	protected function sendConfirmationMail(): bool {
		try {
			$mailer = new ilMimeMail();

			if (ILIAS_VERSION_NUMERIC >= "5.3") {
				$mailer->From(self::dic()->mailMimeSenderFactory()->system());
			}

			$mailer->To($this->support->getEmail());

			$mailer->Subject(self::translate("srsu_confirmation") . ": " . $this->support->getSubject());

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
	public function getSupport(): ilHelpMeSupport {
		return $this->support;
	}


	/**
	 * @param ilHelpMeSupport $support
	 */
	public function setSupport(ilHelpMeSupport $support) {
		$this->support = $support;
	}
}
