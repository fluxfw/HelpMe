<?php

namespace srag\Plugins\HelpMe\Recipient;

use Exception;
use HelpMeSupportGUI;
use ilHelpMePlugin;
use ilMimeMail;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Recipient
 *
 * @package srag\Plugins\HelpMe\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class Recipient {

	use DICTrait;
	use HelpMeTrait;
	const SEND_EMAIL = "send_email";
	const CREATE_JIRA_TICKET = "create_jira_ticket";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var Support
	 */
	protected $support;


	/**
	 * @param string  $recipient
	 * @param Support $support
	 *
	 * @return Recipient|null
	 */
	public static function getRecipient(string $recipient, Support $support)/*: ?Recipient*/ {
		switch ($recipient) {
			case self::SEND_EMAIL:
				return new RecipientSendMail($support);
				break;

			case self::CREATE_JIRA_TICKET:
				return new RecipientCreateJiraTicket($support);
				break;

			default:
				return NULL;
				break;
		}
	}


	/**
	 * Recipient constructor
	 *
	 * @param Support $support
	 */
	protected function __construct(Support $support) {
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

			if (self::version()->is53()) {
				$mailer->From(self::dic()->mailMimeSenderFactory()->system());
			}

			$mailer->To($this->support->getEmail());

			$mailer->Subject(self::plugin()->translate("confirmation", HelpMeSupportGUI::LANG_MODULE_SUPPORT) . ": " . $this->support->getSubject());

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
	 * @return Support
	 */
	public function getSupport(): Support {
		return $this->support;
	}


	/**
	 * @param Support $support
	 */
	public function setSupport(Support $support)/*: void*/ {
		$this->support = $support;
	}
}
