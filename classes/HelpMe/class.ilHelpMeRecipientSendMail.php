<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipient.php";
require_once "Services/Mail/classes/class.ilMail.php";

/**
 * Send support email
 */
class ilHelpMeRecipientSendMail extends ilHelpMeRecipient {

	/**
	 * @var ilMail
	 */
	protected $mail;


	/**
	 * @param ilHelpMeSupport $support
	 * @param ilHelpMeConfig  $config
	 */
	function __construct($support, $config) {
		parent::__construct($support, $config);

		$this->mail = new ilMail(ANONYMOUS_USER_ID);
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
		$errors = $this->mail->sendMail($this->config->getSendEmailAddress(), NULL, NULL, $this->support->getSubject(), $this->support->getBody(), [], [ "system" ], false);

		return (sizeof($errors) === 0);
	}


	/**
	 * Send confirmation email
	 *
	 * @return bool
	 */
	function sendConfirmationMail() {
		$errors = $this->mail->sendMail($this->support->getEmail(), NULL, NULL, $this->pl->txt("srsu_confirmation") . ": "
			. $this->support->getSubject(), $this->support->getBody(), [], [ "system" ], false);

		return (sizeof($errors) === 0);
	}
}
