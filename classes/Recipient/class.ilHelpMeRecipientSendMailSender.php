<?php

/**
 * Send email with reply name and email in ILIAS 5.3
 */
class ilHelpMeRecipientSendMailSender extends ilMailMimeSenderUser {

	/**
	 * @param ilHelpMeSupport $support
	 */
	function __construct(ilHelpMeSupport $support) {
		global $DIC;

		$user = new ilObjUser();
		$user->fullname = $support->getName();
		$user->setEmail($support->getEmail());

		parent::__construct($DIC->settings(), $user);
	}
}
