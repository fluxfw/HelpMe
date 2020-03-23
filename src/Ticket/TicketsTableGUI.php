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
     * @inheritDoc
     */
    protected function getColumnValue(/*string*/
        $column, /*array*/
        $row, /*int*/
        $format = self::DEFAULT_FORMAT
    ) : string {
        switch ($column) {
            case "ticket_project_url_key":
                $column = htmlspecialchars($row["ticket_project"]->getProjectName());
                break;

            default:
                $column = htmlspecialchars($row[$column]);
                break;
        }

        return strval($column);
    }


    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {

    }


    /**
     * @inheritDoc
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

        $this->setData(self::helpMe()->tickets()
            ->getTickets($this->getOrderField(), $this->getOrderDirection(), intval($this->getOffset()), intval($this->getLimit()), $ticket_title, $ticket_project_url_key,
                $ticket_issue_type, $ticket_priority));

        $this->setMaxCount(self::helpMe()->tickets()->getTicketsCount($ticket_title, $ticket_project_url_key, $ticket_issue_type, $ticket_priority));
    }
    /**
     *
     */

    /**
     * @inheritDoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [
            "ticket_title"           => [
                PropertyFormGUI::PROPERTY_CLASS => ilTextInputGUI::class
            ],
            "ticket_project_url_key" => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => ""] + self::helpMe()->projects()->getProjectsOptions(true)
            ],
            "ticket_issue_type"      => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => ""] + self::helpMe()->tickets()->getAvailableIssueTypes()
            ],
            "ticket_priority"        => [
                PropertyFormGUI::PROPERTY_CLASS   => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_OPTIONS => ["" => ""] + self::helpMe()->tickets()->getAvailablePriorities()
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {
        $this->setId(ilHelpMePlugin::PLUGIN_ID . "_tickets");
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("tickets"));
    }
}
