<?php

namespace srag\Plugins\HelpMe\Recipient;

use Exception;
use ilMimeMail;
use srag\Plugins\HelpMe\Config\HelpMeConfig;
use srag\Plugins\HelpMe\Support\HelpMeSupport;

/**
 * Class HelpMeRecipientSendMail
 *
 * @package srag\Plugins\HelpMe\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class HelpMeRecipientSendMail extends HelpMeRecipient {

	/**
	 * HelpMeRecipientSendMail constructor
	 *
	 * @param HelpMeSupport $support
	 */
	public function __construct(HelpMeSupport $support) {
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

			if (self::version()->is53()) {
				$mailer->From(new HelpMeRecipientSendMailSender($this->support));
			} else {
				$mailer->From([ $this->support->getEmail(), $this->support->getName() ]);
			}

			$mailer->To(HelpMeConfig::getSendEmailAddress());

			$mailer->Subject($this->support->getSubject());

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
}
