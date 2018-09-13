<?php

namespace srag\Plugins\HelpMe\Recipient;

use ilHelpMePlugin;
use ilMailMimeSenderUser;
use ilObjUser;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Support\HelpMeSupport;

/**
 * Class HelpMeRecipientSendMailSender
 *
 * Send email with reply name and email in ILIAS 5.3
 *
 * @since   ILIAS 5.3
 *
 * @package srag\Plugins\HelpMe\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class HelpMeRecipientSendMailSender extends ilMailMimeSenderUser {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * HelpMeRecipientSendMailSender constructor
	 *
	 * @param HelpMeSupport $support
	 */
	public function __construct(HelpMeSupport $support) {
		$user = new ilObjUser();
		$user->fullname = $support->getName();
		$user->setEmail($support->getEmail());

		parent::__construct(self::dic()->settings(), $user);
	}
}
