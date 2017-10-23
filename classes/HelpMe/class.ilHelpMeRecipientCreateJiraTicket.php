<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipient.php";

/**
 * Create Jira ticket
 */
class ilHelpMeRecipientCreateJiraTicket extends ilHelpMeRecipient {

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
		return ($this->createJiraTicket() && $this->sendConfirmationMail());
	}


	/**
	 * Create Jira ticket
	 *
	 * @return bool
	 */
	protected function createJiraTicket() {
		return false;
	}
}
