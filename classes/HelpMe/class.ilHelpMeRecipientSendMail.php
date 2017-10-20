<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipient.php";
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
	 * @return bool
	 */
	function sendSupport() {
		return ($this->sendEmail() && $this->sendConfirmationMail());
	}


	/**
	 * Send support email
	 *
	 * @return bool
	 */
	function sendEmail() {
		$mailer = new ilMimeMail();

		$mailer->To($this->config->getSendEmailAddress());

		$mailer->Subject($this->support->getSubject());

		$mailer->Body($this->support->getBody());

		foreach ($this->support->getScreenshots() as $screenshot) {
			$mailer->Attach($screenshot["tmp_name"], $screenshot["type"], "attachment", $screenshot["name"]);
		}

		$mailer->Send();

		return true; // TODO: check error
	}


	/**
	 * Send confirmation email
	 *
	 * @return bool
	 */
	function sendConfirmationMail() {
		$mailer = new ilMimeMail();

		$mailer->To($this->support->getEmail());

		$mailer->Subject($this->pl->txt("srsu_confirmation") . ": " . $this->support->getSubject());

		$mailer->Body($this->support->getBody());

		foreach ($this->support->getScreenshots() as $screenshot) {
			$mailer->Attach($screenshot["tmp_name"], $screenshot["type"], "attachment", $screenshot["name"]);
		}

		$mailer->Send();

		return true; // TODO: check error
	}
}
