<?php

use srag\DIC\DICTrait;

/**
 * Class ilHelpMeRecipientSendMailSender
 *
 * Send email with reply name and email in ILIAS 5.3
 *
 * @since ILIAS 5.3
 */
class ilHelpMeRecipientSendMailSender extends ilMailMimeSenderUser {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * ilHelpMeRecipientSendMailSender constructor
	 *
	 * @param ilHelpMeSupport $support
	 */
	public function __construct(ilHelpMeSupport $support) {
		$user = new ilObjUser();
		$user->fullname = $support->getName();
		$user->setEmail($support->getEmail());

		parent::__construct(self::dic()->settings(), $user);
	}
}
