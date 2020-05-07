<?php

namespace srag\Plugins\HelpMe\Ticket;

use ilCronManager;
use ilDBConstants;
use ilHelpMeConfigGUI;
use ilHelpMeCronPlugin;
use ilHelpMePlugin;
use ilUtil;
use srag\DIC\HelpMe\DICStatic;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\ConfigCtrl;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\IssueTypeField;
use srag\Plugins\HelpMe\RequiredData\Field\Project\ProjectField;
use srag\Plugins\HelpMe\Support\Recipient\Recipient;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\HelpMe\Ticket
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
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
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {
        self::dic()->database()->dropTable(Ticket::TABLE_NAME, false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @return array
     */
    public function getAvailableIssueTypes() : array
    {
        $result = self::dic()->database()->query('SELECT DISTINCT ticket_issue_type FROM ' . Ticket::TABLE_NAME . ' ORDER BY ticket_issue_type ASC');

        $issue_types = [];

        while (($issue_type = $result->fetchAssoc()) !== false) {
            $issue_types[$issue_type["ticket_issue_type"]] = $issue_type["ticket_issue_type"];
        }

        return $issue_types;
    }


    /**
     * @return array
     */
    public function getAvailablePriorities() : array
    {
        $result = self::dic()->database()->query('SELECT DISTINCT ticket_priority FROM ' . Ticket::TABLE_NAME . ' ORDER BY ticket_priority ASC');

        $priorities = [];

        while (($issue_type = $result->fetchAssoc()) !== false) {
            $priorities[$issue_type["ticket_priority"]] = $issue_type["ticket_priority"];
        }

        return $priorities;
    }


    /**
     * @param string $project_url_key
     *
     * @return string
     */
    public function getLink(string $project_url_key = "") : string
    {
        return self::helpMe()->support()->getLink("tickets" . (!empty($project_url_key) ? "_" . $project_url_key : ""));
    }


    /**
     * @param int $ticket_id
     *
     * @return Ticket|null
     */
    public function getTicketById(int $ticket_id)/*: ?Ticket*/
    {
        /**
         * @var Ticket|null $ticket
         */

        $ticket = Ticket::where(["ticket_id" => $ticket_id])->first();

        return $ticket;
    }


    /**
     * @param string $ticket_key
     *
     * @return Ticket|null
     */
    public function getTicketByKey(string $ticket_key)/*: ?Ticket*/
    {
        /**
         * @var Ticket|null $ticket
         */

        $ticket = Ticket::where(["ticket_key" => $ticket_key])->first();

        return $ticket;
    }


    /**
     * @param string|null $sort_by
     * @param string|null $sort_by_direction
     * @param int|null    $limit_start
     * @param int|null    $limit_end
     * @param string      $ticket_title
     * @param string      $ticket_project_url_key
     * @param string      $ticket_issue_type
     * @param string      $ticket_priority
     *
     * @return array
     */
    public function getTickets(
        string $sort_by = null,
        string $sort_by_direction = null,
        int $limit_start = null,
        int $limit_end = null,
        string $ticket_title = "",
        string $ticket_project_url_key = "",
        string $ticket_issue_type = "",
        string $ticket_priority = ""
    ) : array {

        $sql = 'SELECT *';

        $sql .= $this->getTicketsQuery($sort_by, $sort_by_direction, $limit_start, $limit_end, $ticket_title, $ticket_project_url_key, $ticket_issue_type, $ticket_priority);

        $result = self::dic()->database()->query($sql);

        $tickets = [];

        while (($row = $result->fetchAssoc()) !== false) {
            $row["ticket_project"] = self::helpMe()->projects()->getProjectByUrlKey($row["ticket_project_url_key"]);

            $tickets[$row["ticket_id"]] = $row;
        }

        return $tickets;
    }


    /**
     * @param string $ticket_title
     * @param string $ticket_project_url_key
     * @param string $ticket_issue_type
     * @param string $ticket_priority
     *
     * @return int
     */
    public function getTicketsCount(string $ticket_title = "", string $ticket_project_url_key = "", string $ticket_issue_type = "", string $ticket_priority = "") : int
    {

        $sql = 'SELECT COUNT(ticket_id) AS count';

        $sql .= $this->getTicketsQuery(null, null, null, null, $ticket_title, $ticket_project_url_key, $ticket_issue_type, $ticket_priority);

        $result = self::dic()->database()->query($sql);

        if (($row = $result->fetchAssoc()) !== false) {
            return intval($row["count"]);
        }

        return 0;
    }


    /**
     * @param string|null $sort_by
     * @param string|null $sort_by_direction
     * @param int|null    $limit_start
     * @param int|null    $limit_end
     * @param string      $ticket_title
     * @param string      $ticket_project_url_key
     * @param string      $ticket_issue_type
     * @param string      $ticket_priority
     *
     * @return string
     */
    private function getTicketsQuery(
        string $sort_by = null,
        string $sort_by_direction = null,
        int $limit_start = null,
        int $limit_end = null,
        string $ticket_title = "",
        string $ticket_project_url_key = "",
        string $ticket_issue_type = "",
        string $ticket_priority = ""
    ) : string {

        $sql = ' FROM ' . Ticket::TABLE_NAME;

        $wheres = [];

        if (!empty($ticket_title)) {
            $wheres[] = self::dic()->database()->like("ticket_title", ilDBConstants::T_TEXT, '%' . $ticket_title . '%');
        }

        if (!empty($ticket_project_url_key)) {
            $wheres[] = 'ticket_project_url_key=' . self::dic()->database()->quote($ticket_project_url_key, ilDBConstants::T_TEXT);
        }

        if (!empty($ticket_issue_type)) {
            $wheres[] = 'ticket_issue_type=' . self::dic()->database()->quote($ticket_issue_type, ilDBConstants::T_TEXT);
        }

        if (!empty($ticket_priority)) {
            $wheres[] = 'ticket_priority=' . self::dic()->database()->quote($ticket_priority, ilDBConstants::T_TEXT);
        }

        if (count($wheres) > 0) {
            $sql .= ' WHERE ' . implode(" AND ", $wheres);
        }

        if ($sort_by !== null && $sort_by_direction !== null) {
            $sql .= ' ORDER BY ' . self::dic()->database()->quoteIdentifier($sort_by) . ' ' . $sort_by_direction;
        }

        if ($limit_start !== null && $limit_end !== null) {
            $sql .= ' LIMIT ' . self::dic()->database()->quote($limit_start, ilDBConstants::T_INTEGER) . ',' . self::dic()->database()
                    ->quote($limit_end, ilDBConstants::T_INTEGER);
        }

        return $sql;
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        Ticket::updateDB();
    }


    /**
     * @param bool $check_has_one_project_at_least_read_access
     *
     * @return bool
     */
    public function isEnabled(bool $check_has_one_project_at_least_read_access = true) : bool
    {
        return (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_RECIPIENT) === Recipient::CREATE_JIRA_TICKET
            && (!$check_has_one_project_at_least_read_access || self::helpMe()->projects()->hasOneProjectAtLeastReadAccess())
            && file_exists(__DIR__ . "/../../../../../Cron/CronHook/HelpMeCron/vendor/autoload.php")
            && DICStatic::plugin(ilHelpMeCronPlugin::class)->getPluginObject()->isActive()
            && ilCronManager::isJobActive(FetchJiraTicketsJob::CRON_JOB_ID));
    }


    /**
     *
     */
    public function removeTickets()/*: void*/
    {
        Ticket::truncateDB();
    }


    /**
     * @param Ticket[] $tickets
     */
    public function replaceWith(array $tickets)/*: void*/
    {
        $this->removeTickets();

        foreach ($tickets as $ticket) {
            $this->storeTicket($ticket);
        }
    }


    /**
     *
     */
    public function showUsageConfigHint()/*: void*/
    {
        $usage_ids = [];

        if (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_RECIPIENT) === Recipient::CREATE_JIRA_TICKET) {

            if (!$this->isEnabled(false)) {

                $usage_ids[] = "usage_1_info";
            }

            if (!$this->isEnabled()) {

                $usage_ids[] = "usage_2_info";
            }
        }

        if (!empty(self::helpMe()->projects()->getProjects())) {

            if (empty(self::helpMe()
                ->requiredData()
                ->fields()
                ->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [ProjectField::getType()]))
            ) {

                $usage_ids[] = "usage_3_info";
            }

            if (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_RECIPIENT) === Recipient::CREATE_JIRA_TICKET) {

                if (empty(self::helpMe()
                    ->requiredData()
                    ->fields()
                    ->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [IssueTypeField::getType()]))
                ) {

                    $usage_ids[] = "usage_4_info";
                }
            }
        }

        $info = [];
        foreach ($usage_ids as $usage_id) {

            if (!self::helpMe()->config()->getValue(ConfigFormGUI::KEY_USAGE_HIDDEN)[$usage_id]) {

                $text = self::plugin()->translate($usage_id, ConfigCtrl::LANG_MODULE);

                self::dic()->ctrl()->setParameterByClass(ConfigCtrl::class, TicketsGUI::GET_PARAM_USAGE_ID, $usage_id);
                $hide_button = self::dic()->ui()->factory()->button()->standard(self::plugin()
                    ->translate("usage_hide", ConfigCtrl::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTargetByClass(ilHelpMeConfigGUI::class, ConfigCtrl::class, ConfigCtrl::CMD_HIDE_USAGE));
                self::dic()->ctrl()->setParameterByClass(ConfigCtrl::class, TicketsGUI::GET_PARAM_USAGE_ID, null);

                if (self::version()->is54()) {
                    $info[] = self::dic()->ui()->factory()->messageBox()->info($text)->withButtons([$hide_button]);
                } else {
                    $info[] = $text;
                    $info[] = "<br>";
                    $info[] = $hide_button;
                    $info[] = "<br><br>";
                }
            }
        }
        if (!empty($info)) {
            ilUtil::sendInfo(self::output()->getHTML($info));
        }
    }


    /**
     * @param Ticket $ticket
     */
    public function storeTicket(Ticket $ticket)/*: void*/
    {
        $ticket->store();
    }
}
