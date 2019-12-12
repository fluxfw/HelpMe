<?php

namespace srag\Plugins\HelpMe\Support\Recipient;

use ilHelpMePlugin;
use ilMailMimeSenderUser;
use ilObjUser;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class RecipientSendMailSender
 *
 * Send email with reply name and email in ILIAS 5.3
 *
 * @since   ILIAS 5.3
 *
 * @package srag\Plugins\HelpMe\Support\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecipientSendMailSender extends ilMailMimeSenderUser
{

    use DICTrait;
    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


    /**
     * RecipientSendMailSender constructor
     *
     * @param Support $support
     */
    public function __construct(Support $support)
    {
        $user = new ilObjUser();
        $user->fullname = $support->getName();
        $user->setEmail($support->getEmail());

        parent::__construct(self::dic()->settings(), $user);
    }
}
