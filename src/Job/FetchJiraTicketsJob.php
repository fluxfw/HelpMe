<?php

namespace srag\Plugins\HelpMe\Job;

use ilCronJob;
use ilCronJobResult;
use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Exception\HelpMeException;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class FetchJiraTicketsJob
 *
 * @package srag\Plugins\HelpMe\Job
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FetchJiraTicketsJob extends ilCronJob
{

    use DICTrait;
    use HelpMeTrait;
    const CRON_JOB_ID = ilHelpMePlugin::PLUGIN_ID . "_fetch_jira_tickets";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const LANG_MODULE_CRON = "cron";


    /**
     * FetchJiraTicketsJob constructor
     */
    public function __construct()
    {

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
        return ilHelpMePlugin::PLUGIN_NAME . ": " . self::plugin()->translate(self::CRON_JOB_ID, self::LANG_MODULE_CRON);
    }


    /**
     * @inheritDoc
     */
    public function getDescription() : string
    {
        return self::plugin()->translate(self::CRON_JOB_ID . "_description", self::LANG_MODULE_CRON);
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
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_IN_HOURS;
    }


    /**
     * @inheritDoc
     */
    public function getDefaultScheduleValue()/*:?int*/
    {
        return 1;
    }


    /**
     * @inheritDoc
     */
    public function run() : ilCronJobResult
    {
        $result = new ilCronJobResult();

        if (!self::helpMe()->ticket()->isEnabled()) {
            throw new HelpMeException("Tickets are not enabled");
        }

        $jira_curl = self::helpMe()->support()->initJiraCurl();

        $projects = self::helpMe()->project()->getProjects(true);

        $jsons = [];
        foreach ($projects as $project) {
            $jsons = array_merge($jsons, $jira_curl->getTicketsOfProject($project->getProjectKey(), self::helpMe()->project()
                ->getIssueTypesOptions($project)));
        }

        $tickets = [];
        foreach ($jsons as $json) {
            $tickets[] = self::helpMe()->ticket()->factory()->fromJiraJson($json);
        }

        self::helpMe()->ticket()->replaceWith($tickets);

        $result->setStatus(ilCronJobResult::STATUS_OK);

        $result->setMessage(self::plugin()->translate("status", self::LANG_MODULE_CRON, [
            count($tickets),
            count($projects)
        ]));

        return $result;
    }
}
