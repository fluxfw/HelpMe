<?php

namespace srag\Plugins\HelpMe\Recipient;

use Exception;
use ilHelpMePlugin;
use ilMimeMail;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Support\HelpMeSupport;

/**
 * Class HelpMeRecipient
 *
 * @package srag\Plugins\HelpMe\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class HelpMeRecipient {

	use DICTrait;
	const SEND_EMAIL = "send_email";
	const CREATE_JIRA_TICKET = "create_jira_ticket";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var HelpMeSupport
	 */
	protected $support;


	/**
	 * @param string        $recipient
	 * @param HelpMeSupport $support
	 *
	 * @return HelpMeRecipient|null
	 */
	public static function getRecipient(string $recipient, HelpMeSupport $support)/*: ?HelpMeRecipient*/ {
		switch ($recipient) {
			case self::SEND_EMAIL:
				return new HelpMeRecipientSendMail($support);
				break;

			case self::CREATE_JIRA_TICKET:
				return new HelpMeRecipientCreateJiraTicket($support);
				break;

			default:
				return NULL;
				break;
		}
	}


	/**
	 * HelpMeRecipient constructor
	 *
	 * @param HelpMeSupport $support
	 */
	protected function __construct(HelpMeSupport $support) {
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

			$mailer->Subject(self::plugin()->translate("srsu_confirmation") . ": " . $this->support->getSubject());

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
	 * @return HelpMeSupport
	 */
	public function getSupport(): HelpMeSupport {
		return $this->support;
	}


	/**
	 * @param HelpMeSupport $support
	 */
	public function setSupport(HelpMeSupport $support)/*: void*/ {
		$this->support = $support;
	}
}
