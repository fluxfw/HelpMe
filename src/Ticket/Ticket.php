<?php

namespace srag\Plugins\HelpMe\Ticket;

use ActiveRecord;
use arConnector;
use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Ticket
 *
 * @package srag\Plugins\HelpMe\Ticket
 */
class Ticket extends ActiveRecord
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TABLE_NAME = "ui_uihk_" . ilHelpMePlugin::PLUGIN_ID . "_ticket";
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     * @con_sequence     true
     */
    protected $ticket_id;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $ticket_issue_type = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $ticket_key = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $ticket_priority = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $ticket_project_url_key = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $ticket_title = "";


    /**
     * Ticket constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/
        $primary_key_value = 0,
        arConnector $connector = null
    ) {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @return int
     */
    public function getTicketId() : int
    {
        return $this->ticket_id;
    }


    /**
     * @param int $ticket_id
     */
    public function setTicketId(int $ticket_id) : void
    {
        $this->ticket_id = $ticket_id;
    }


    /**
     * @return string
     */
    public function getTicketIssueType() : string
    {
        return $this->ticket_issue_type;
    }


    /**
     * @param string $ticket_issue_type
     */
    public function setTicketIssueType(string $ticket_issue_type) : void
    {
        $this->ticket_issue_type = $ticket_issue_type;
    }


    /**
     * @return string
     */
    public function getTicketKey() : string
    {
        return $this->ticket_key;
    }


    /**
     * @param string $ticket_key
     */
    public function setTicketKey(string $ticket_key) : void
    {
        $this->ticket_key = $ticket_key;
    }


    /**
     * @return string
     */
    public function getTicketPriority() : string
    {
        return $this->ticket_priority;
    }


    /**
     * @param string $ticket_priority
     */
    public function setTicketPriority(string $ticket_priority) : void
    {
        $this->ticket_priority = $ticket_priority;
    }


    /**
     * @return string
     */
    public function getTicketProjectUrlKey() : string
    {
        return $this->ticket_project_url_key;
    }


    /**
     * @param string $ticket_project_url_key
     */
    public function setTicketProjectUrlKey(string $ticket_project_url_key) : void
    {
        $this->ticket_project_url_key = $ticket_project_url_key;
    }


    /**
     * @return string
     */
    public function getTicketTitle() : string
    {
        return $this->ticket_title;
    }


    /**
     * @param string $ticket_title
     */
    public function setTicketTitle(string $ticket_title) : void
    {
        $this->ticket_title = $ticket_title;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "ticket_id":
                return intval($field_value);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}
