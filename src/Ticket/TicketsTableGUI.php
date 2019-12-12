<?php

namespace srag\Plugins\HelpMe\Ticket;

use ilHelpMePlugin;
use ilSelectInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\TableGUI\TableGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class TicketsTableGUI
 *
 * @package srag\Plugins\HelpMe\Ticket
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TicketsTableGUI extends TableGUI
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const LANG_MODULE = TicketsGUI::LANG_MODULE;


    /**
     * TicketsTableGUI constructor
     *
     * @param TicketsGUI $parent
     * @param string     $parent_cmd
     */
    public function __construct(TicketsGUI $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritdoc
     */
    protected function getColumnValue(/*string*/
        $column, /*array*/
        $row, /*int*/
        $format = self::DEFAULT_FORMAT
    ) : string {
        switch ($column) {
            case "ticket_project_url_key":
                $column = $row["ticket_project"]->getProjectName();
                break;

            default:
                $column = $row[$column];
                break;
        }

        return strval($column);
    }


    /**
     * @inheritdoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "ticket_title"           => "ticket_title",
            "ticket_project_url_key" => "ticket_project_url_key",
            "ticket_issue_type"      => "ticket_issue_type",
            "ticket_priority"        => "ticket_priority"
        ];

        $columns = array_map(function (string $key) : array {
            return [
                "id"      => $key,
                "default" => true,
                "sort"    => true
            ];
        }, $columns);

        return $columns;
    }


    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {

    }


    /**
     * @inheritdoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setDefaultOrderField("ticket_title");
        $this->setDefaultOrderDirection("asc");

        // Fix stupid ilTable2GUI !!! ...
        $this->determineLimit();
        $this->determineOffsetAndOrder();

        $filter = $this->getFilterValues();

        $ticket_title = $filter["ticket_title"];
        $ticket_project_url_key = $filter["ticket_project_url_key"];
        $ticket_issue_type = $filter["ticket_issue_type"];
        $ticket_priority = $filter["ticket_priority"];

        $this->setData(self::helpMe()->ticket()
            ->getTickets($this->getOrderField(), $this->getOrderDirection(), intval($this->getOffset()), intval($this->getLimit()), $ticket_title, $ticket_project_url_key,
                $ticket_issue_type, $ticket_priority));

        $this->setMaxCount(self::helpMe()->ticket()->getTicketsCount($ticket_title, $ticket_project_url_key, $ticket_issue_type, $ticket_priority));
    }
    /**
     *
     */

    /**
     * @inheritdoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [
            "ticket_title"           => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
            ],
            "ticket_project_url_key" => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => ""] + self::helpMe()->project()->getProjectsOptions(true)
            ],
            "ticket_issue_type"      => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => ""] + self::helpMe()->ticket()->getAvailableIssueTypes()
            ],
            "ticket_priority"        => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => ""] + self::helpMe()->ticket()->getAvailablePriorities()
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    protected function initId()/*: void*/
    {
        $this->setId("srsu_tickets");
    }


    /**
     * @inheritdoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("tickets"));
    }
}
