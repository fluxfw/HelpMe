<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/Recipient/class.ilHelpMeRecipient.php";
require_once "Services/Mail/classes/class.ilMimeMail.php";

/**
 * Send support email
 */
class ilHelpMeRecipientSendMail extends ilHelpMeRecipient {

	/**
	 * @param ilHelpMeSupport $support
	 * @param ilHelpMeConfig  $config
	 */
	function __construct($support, $config) {
		parent::__construct($support, $config);
	}


	/**
	 * Send support to recipient
	 *
	 * @return bool
	 */
	function sendSupportToRecipient() {
		return ($this->sendEmail() && $this->sendConfirmationMail());
	}


	/**
	 * Send support email
	 *
	 * @return bool
	 */
	function sendEmail() {
		try {
			$mailer = new ilMimeMail();

			if (ILIAS_VERSION_NUMERIC >= "5.3") {
				require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/Recipient/class.ilHelpMeRecipientSendMailSender.php";

				$mailer->From(new ilHelpMeRecipientSendMailSender($this->support));
			} else {
				$mailer->From([ $this->support->getEmail(), $this->support->getName() ]);
			}

			$mailer->To($this->config->getSendEmailAddress());

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
