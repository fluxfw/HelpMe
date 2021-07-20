<?php

namespace srag\Plugins\HelpMe\Support\Recipient;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\HelpMe\Support\Recipient
 */
final class Factory
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @param string  $recipient
     * @param Support $support
     *
     * @return Recipient|null
     */
    public function newInstance(string $recipient, Support $support) : ?Recipient
    {
        switch ($recipient) {
            case Recipient::SEND_EMAIL:
                return new RecipientSendMail($support);

            case Recipient::CREATE_JIRA_TICKET:
                return new RecipientCreateJiraTicket($support);

            default:
                return null;
        }
    }
}
