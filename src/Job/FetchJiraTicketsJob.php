<?php

namespace srag\Plugins\HelpMe\Job;

use ilCronJob;
use ilCronJobResult;
use ilCurlConnectionException;
use ilHelpMePlugin;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\DICTrait;
use srag\DIC\HelpMe\Exception\DICException;
use srag\JiraCurl\HelpMe\Exception\JiraCurlException;
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
     * Get id
     *
     * @return string
     */
    public function getId() : string
    {
        return self::CRON_JOB_ID;
    }


    /**
     * @return string
     */
    public function getTitle() : string
    {
        return ilHelpMePlugin::PLUGIN_NAME . ": " . self::plugin()->translate(self::CRON_JOB_ID, self::LANG_MODULE_CRON);
    }


    /**
     * @return string
     */
    public function getDescription() : string
    {
        return self::plugin()->translate(self::CRON_JOB_ID . "_description", self::LANG_MODULE_CRON);
    }


    /**
     * Is to be activated on "installation"
     *
     * @return boolean
     */
    public function hasAutoActivation() : bool
    {
        return true;
    }


    /**
     * Can the schedule be configured?
     *
     * @return boolean
     */
    public function hasFlexibleSchedule() : bool
    {
        return true;
    }


    /**
     * Get schedule type
     *
     * @return int
     */
    public function getDefaultScheduleType() : int
    {
        return self::SCHEDULE_TYPE_IN_HOURS;
    }


    /**
     * Get schedule value
     *
     * @return int|array
     */
    public function getDefaultScheduleValue()
    {
        return 1;
    }


    /**
     * Run job
     *
     * @return ilCronJobResult
     *
     * @throws ActiveRecordConfigException
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     * @throws HelpMeException
     * @throws DICException
     */
    public function run() : ilCronJobResult
    {
        $result = new ilCronJobResult();

        if (!self::tickets()->isEnabled()) {
            throw new HelpMeException("Tickets are not enabled");
        }

        $jira_curl = self::supports()->initJiraCurl();

        $projects = self::projects()->getProjects(true);

        $jsons = [];
        foreach ($projects as $project) {
            $jsons = array_merge($jsons, $jira_curl->getTicketsOfProject($project->getProjectKey(), self::projects()
                ->getIssueTypesOptions($project)));
        }

        $tickets = [];
        foreach ($jsons as $json) {
            $tickets[] = self::tickets()->factory()->fromJiraJson($json);
        }

        self::tickets()->replaceWith($tickets);

        $result->setStatus(ilCronJobResult::STATUS_OK);

        $result->setMessage(self::plugin()->translate("status", self::LANG_MODULE_CRON, [
            count($tickets),
            count($projects)
        ]));

        return $result;
    }
}
