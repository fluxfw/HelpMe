<?php

namespace srag\Plugins\HelpMe\Ticket;

use ilCronJob;
use ilCronJobResult;
use ilCronManager;
use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class FetchJiraTicketsJob
 *
 * @package srag\Plugins\HelpMe\Ticket
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FetchJiraTicketsJob extends ilCronJob
{

    use DICTrait;
    use HelpMeTrait;

    const CRON_JOB_ID = ilHelpMePlugin::PLUGIN_ID . "_fetch_jira_tickets";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


    /**
     * FetchJiraTicketsJob constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_IN_HOURS;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleValue() : ?int
    {
        return 1;
    }


    /**
     * @inheritDoc
     */
    public function getDescription() : string
    {
        return self::plugin()->translate("fetch_jira_tickets_description", TicketsGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function getId() : string
    {
        return self::CRON_JOB_ID;
    }


    /**
     * @inheritDoc
     */
    public function getTitle() : string
    {
        return ilHelpMePlugin::PLUGIN_NAME . ": " . self::plugin()->translate("fetch_jira_tickets", TicketsGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function hasAutoActivation() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function run() : ilCronJobResult
    {
        $result = new ilCronJobResult();

        if (!self::helpMe()->tickets()->isEnabled()) {
            $result->setStatus(ilCronJobResult::STATUS_NO_ACTION);

            return $result;
        }

        $jira_curl = self::helpMe()->support()->initJiraCurl();

        $projects = self::helpMe()->projects()->getProjects(true);

        $jsons = [];
        foreach ($projects as $project) {
            $jsons = array_merge($jsons, $jira_curl->getTicketsOfProject($project->getProjectKey(), self::helpMe()->projects()
                ->getIssueTypesOptions($project)));

            ilCronManager::ping($this->getId());
        }

        $tickets = [];
        foreach ($jsons as $json) {
            $tickets[] = self::helpMe()->tickets()->factory()->fromJiraJson($json);

            ilCronManager::ping($this->getId());
        }

        self::helpMe()->tickets()->replaceWith($tickets);

        $result->setStatus(ilCronJobResult::STATUS_OK);

        $result->setMessage(self::plugin()->translate("fetch_jira_tickets_status", TicketsGUI::LANG_MODULE, [
            count($tickets),
            count($projects)
        ]));

        return $result;
    }
}
