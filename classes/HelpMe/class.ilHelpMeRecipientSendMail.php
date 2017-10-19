<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipient.php";

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
		echo "Send email ...";
	}
}
