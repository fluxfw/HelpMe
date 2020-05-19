<?php

namespace srag\Plugins\HelpMe\Support\Recipient;

use ilHelpMePlugin;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\HelpMe\Support\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @param string  $recipient
     * @param Support $support
     *
     * @return Recipient|null
     *
     * @throws ActiveRecordConfigException
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
