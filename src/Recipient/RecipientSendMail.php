<?php

namespace srag\Plugins\HelpMe\Recipient;

use Exception;
use ilMimeMail;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Support\Support;

/**
 * Class RecipientSendMail
 *
 * @package srag\Plugins\HelpMe\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecipientSendMail extends Recipient {

	/**
	 * RecipientSendMail constructor
	 *
	 * @param Support $support
	 */
	public function __construct(Support $support) {
		parent::__construct($support);
	}


	/**
	 * @inheritdoc
	 */
	public function sendSupportToRecipient(): bool {
		return ($this->sendEmail() && $this->sendConfirmationMail());
	}


	/**
	 * Send support email
	 *
	 * @return bool
	 */
	protected function sendEmail(): bool {
		try {
			$mailer = new ilMimeMail();

			$mailer->From(new RecipientSendMailSender($this->support));

			$mailer->To(Config::getSendEmailAddress());

			$mailer->Subject($this->support->getSubject());

			$mailer->Body($this->support->getBody("email"));

			foreach ($this->support->getScreenshots() as $screenshot) {
				$mailer->Attach($screenshot->getPath(), $screenshot->getMimeType(), "attachment", $screenshot->getName());
			}

			$mailer->Send();

			return true;
		} catch (Exception $ex) {
			return false;
		}
	}
}