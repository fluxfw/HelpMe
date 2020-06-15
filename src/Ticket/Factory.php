<?php

namespace srag\Plugins\HelpMe\Ticket;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Exception\HelpMeException;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\HelpMe\Ticket
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
     * @param array $json
     *
     * @return Ticket
     *
     * @throws HelpMeException
     */
    public function fromJiraJson(array $json) : Ticket
    {
        $ticket = $this->newInstance();

        if (empty($json["key"])) {
            throw new HelpMeException("Key not set");
        }
        $ticket->setTicketKey($json["key"]);

        if (empty($json["fields"]["summary"])) {
            throw new HelpMeException("Summary of {$ticket->getTicketKey()} not set");
        }
        $ticket->setTicketTitle(trim($json["fields"]["summary"]));

        if (empty($json["fields"]["project"]["key"])) {
            throw new HelpMeException("Project key of {$ticket->getTicketKey()} not set");
        }
        $ticket->setTicketProjectUrlKey(self::helpMe()->projects()->getProjectByKey($json["fields"]["project"]["key"])->getProjectUrlKey());

        if (empty($json["fields"]["issuetype"]["name"])) {
            throw new HelpMeException("Issue type of {$ticket->getTicketKey()} not set");
        }
        $ticket->setTicketIssueType($json["fields"]["issuetype"]["name"]);

        if (empty($json["fields"]["priority"]["name"])) {
            throw new HelpMeException("Priority of {$ticket->getTicketKey()} not set");
        }
        $ticket->setTicketPriority($json["fields"]["priority"]["name"]);

        return $ticket;
    }


    /**
     * @param Support $support
     * @param string  $ticket_key
     * @param string  $ticket_title
     *
     * @return Ticket
     */
    public function fromSupport(Support $support, string $ticket_key, string $ticket_title) : Ticket
    {
        $ticket = $this->newInstance();

        $ticket->setTicketKey($ticket_key);

        $ticket->setTicketTitle(trim($ticket_title));

        $ticket->setTicketProjectUrlKey($support->getProject()->getProjectUrlKey());

        $ticket->setTicketIssueType($support->getIssueType());

        $ticket->setTicketPriority($support->getPriority());

        return $ticket;
    }


    /**
     * @return FetchJiraTicketsJob
     */
    public function newFetchJiraTicketsJobInstance() : FetchJiraTicketsJob
    {
        $job = new FetchJiraTicketsJob();

        return $job;
    }


    /**
     * @return Ticket
     */
    public function newInstance() : Ticket
    {
        $ticket = new Ticket();

        return $ticket;
    }


    /**
     * @param TicketsGUI $parent
     * @param string     $cmd
     *
     * @return TicketsTableGUI
     */
    public function newTableInstance(TicketsGUI $parent, string $cmd = TicketsGUI::CMD_LIST_TICKETS) : TicketsTableGUI
    {
        $table = new TicketsTableGUI($parent, $cmd);

        return $table;
    }
}
