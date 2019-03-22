<?php

namespace srag\Plugins\HelpMe\Recipient;

use ilHelpMePlugin;
use ilMimeMail;
use PHPMailer\PHPMailer\Exception as phpmailerException;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\DICTrait;
use srag\DIC\HelpMe\Exception\DICException;
use srag\HelpMe\Exception\HelpMeException;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportGUI;
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
	 *
	 * @throws ActiveRecordConfigException
	 */
	public static function getRecipient(string $recipient, Support $support)/*: ?Recipient*/ {
		switch ($recipient) {
			case self::SEND_EMAIL:
				return new RecipientSendMail($support);

			case self::CREATE_JIRA_TICKET:
				return new RecipientCreateJiraTicket($support);

			default:
				return null;
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
	 * @throws HelpMeException
	 */
	public abstract function sendSupportToRecipient()/*: void*/
	;


	/**
	 * Send confirmation email
	 *
	 * @throws DICException
	 * @throws HelpMeException
	 * @throws phpmailerException
	 */
	protected function sendConfirmationMail()/*: void*/ {
		$mailer = new ilMimeMail();

		$mailer->From(self::dic()->mailMimeSenderFactory()->system());

		$mailer->To($this->support->getEmail());

		$mailer->Subject(self::plugin()->translate("confirmation", SupportGUI::LANG_MODULE_SUPPORT) . ": " . $this->support->getSubject());

		$mailer->Body($this->support->getBody());

		foreach ($this->support->getScreenshots() as $screenshot) {
			$mailer->Attach($screenshot->getPath(), $screenshot->getMimeType(), "attachment", $screenshot->getName());
		}

		if (!$mailer->Send()) {
			throw new HelpMeException("Mailer returns not true");
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
