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
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Exception\HelpMeException;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class FetchJiraTicketsJob
 *
 * @package rag\Plugins\HelpMe\Job
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FetchJiraTicketsJob extends ilCronJob {

	use DICTrait;
	use HelpMeTrait;
	const CRON_JOB_ID = ilHelpMePlugin::PLUGIN_ID . "_fetch_jira_tickets";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const LANG_MODULE_CRON = "cron";


	/**
	 * FetchJiraTicketsJob constructor
	 */
	public function __construct() {

	}


	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getId(): string {
		return self::CRON_JOB_ID;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		return ilHelpMePlugin::PLUGIN_NAME . ": " . self::plugin()->translate(self::CRON_JOB_ID, self::LANG_MODULE_CRON);
	}


	/**
	 * @return string
	 */
	public function getDescription(): string {
		return self::plugin()->translate(self::CRON_JOB_ID . "_description", self::LANG_MODULE_CRON);
	}


	/**
	 * Is to be activated on "installation"
	 *
	 * @return boolean
	 */
	public function hasAutoActivation(): bool {
		return true;
	}


	/**
	 * Can the schedule be configured?
	 *
	 * @return boolean
	 */
	public function hasFlexibleSchedule(): bool {
		return true;
	}


	/**
	 * Get schedule type
	 *
	 * @return int
	 */
	public function getDefaultScheduleType(): int {
		return self::SCHEDULE_TYPE_IN_HOURS;
	}


	/**
	 * Get schedule value
	 *
	 * @return int|array
	 */
	public function getDefaultScheduleValue() {
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
	public function run(): ilCronJobResult {
		$result = new ilCronJobResult();

		$jira_curl = new JiraCurl();

		$jira_curl->setJiraDomain(Config::getField(Config::KEY_JIRA_DOMAIN));

		$jira_curl->setJiraAuthorization(JiraCurl::AUTHORIZATION_USERNAMEPASSWORD);

		$jira_curl->setJiraUsername(Config::getField(Config::KEY_JIRA_USERNAME));
		$jira_curl->setJiraPassword(Config::getField(Config::KEY_JIRA_PASSWORD));

		$jsons = [];
		foreach (self::projects()->getProjects() as $project) {
			$jsons = array_merge($jsons, $jira_curl->getTicketsOfProject($project->getProjectKey(), [ $project->getProjectIssueType() ]));
		}

		$tickets = [];
		foreach ($jsons as $json) {
			$tickets[] = self::tickets()->factory()->fromJiraJson($json);
		}

		self::tickets()->replaceWith($tickets);

		$result->setStatus(ilCronJobResult::STATUS_OK);

		$result->setMessage(self::plugin()->translate("status", self::LANG_MODULE_CRON, [
			count($jsons)
		]));

		return $result;
	}
}
