<?php

namespace srag\Plugins\HelpMe\Recipient;

use ilHelpMePlugin;
use ilMailMimeSenderUser;
use ilObjUser;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Support\ilHelpMeSupport;

/**
 * Class ilHelpMeRecipientSendMailSender
 *
 * Send email with reply name and email in ILIAS 5.3
 *
 * @package srag\Plugins\HelpMe\Recipient
 * @since   ILIAS 5.3
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
