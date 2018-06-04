<?php

/**
 * Send email with reply name and email in ILIAS 5.3
 */
class ilHelpMeRecipientSendMailSender extends ilMailMimeSenderUser {

	use \srag\DICTrait;


	/**
	 * @param ilHelpMeSupport $support
	 */
	public function __construct(ilHelpMeSupport $support) {
		$user = new ilObjUser();
		$user->fullname = $support->getName();
		$user->setEmail($support->getEmail());

		parent::__construct($this->ilSetting, $user);
	}
}
