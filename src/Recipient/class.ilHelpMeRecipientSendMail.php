<?php

namespace srag\Plugins\HelpMe\Recipient;

use Exception;
use ilMimeMail;
use srag\Plugins\HelpMe\Config\ilHelpMeConfig;
use srag\Plugins\HelpMe\Support\ilHelpMeSupport;

/**
 * Class ilHelpMeRecipientSendMail
 *
 * @package srag\Plugins\HelpMe\Recipient
 */
class ilHelpMeRecipientSendMail extends ilHelpMeRecipient {

	/**
	 * ilHelpMeRecipientSendMail constructor
	 *
	 * @param ilHelpMeSupport $support
	 */
	public function __construct(ilHelpMeSupport $support) {
		parent::__construct($support);
	}


	/**
	 * Send support to recipient
	 *
	 * @return bool
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

			if (ILIAS_VERSION_NUMERIC >= "5.3") {
				$mailer->From(new ilHelpMeRecipientSendMailSender($this->support));
			} else {
				$mailer->From([ $this->support->getEmail(), $this->support->getName() ]);
			}

			$mailer->To(ilHelpMeConfig::getSendEmailAddress());

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
