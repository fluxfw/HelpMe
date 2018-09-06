<?php

namespace srag\Plugins\HelpMe\Recipient;

use srag\JiraCurl\JiraCurl;
use srag\Plugins\HelpMe\Config\HelpMeConfig;
use srag\Plugins\HelpMe\Support\HelpMeSupport;

/**
 * Class HelpMeRecipientCreateJiraTicket
 *
 * @package srag\Plugins\HelpMe\Recipient
 */
class HelpMeRecipientCreateJiraTicket extends HelpMeRecipient {

	/**
	 * @var JiraCurl
	 */
	protected $jira_curl;
	/**
	 * @var string
	 */
	protected $issue_key;


	/**
	 * HelpMeRecipientCreateJiraTicket constructor
	 *
	 * @param HelpMeSupport $support
	 */
	public function __construct(HelpMeSupport $support) {
		parent::__construct($support);

		$this->jira_curl = new JiraCurl();

		$this->jira_curl->setJiraDomain(HelpMeConfig::getJiraDomain());

		$this->jira_curl->setJiraAuthorization(HelpMeConfig::getJiraAuthorization());

		$this->jira_curl->setJiraUsername(HelpMeConfig::getJiraUsername());
		$this->jira_curl->setJiraPassword(HelpMeConfig::getJiraPassword());

		$this->jira_curl->setJiraConsumerKey(HelpMeConfig::getJiraConsumerKey());
		$this->jira_curl->setJiraPrivateKey(HelpMeConfig::getJiraPrivateKey());
		$this->jira_curl->setJiraAccessToken(HelpMeConfig::getJiraAccessToken());
	}


	/**
	 * @inheritdoc
	 */
	public function sendSupportToRecipient(): bool {
		return ($this->createJiraTicket() && $this->addScreenshoots() && $this->sendConfirmationMail());
	}


	/**
	 * Create Jira ticket
	 *
	 * @return bool
	 */
	protected function createJiraTicket(): bool {
		$issue_key = $this->jira_curl->createJiraIssueTicket(HelpMeConfig::getJiraProjectKey(), HelpMeConfig::getJiraIssueType(), $this->support->getSubject(), $this->support->getBody("jira"));

		if ($issue_key === false) {
			return false;
		}

		$this->issue_key = $issue_key;

		return true;
	}


	/**
	 * Add screenshots to Jira ticket
	 *
	 * @return bool
	 */
	protected function addScreenshoots(): bool {
		foreach ($this->support->getScreenshots() as $screenshot) {
			if (!$this->jira_curl->addAttachmentToIssue($this->issue_key, $screenshot["name"], $screenshot["type"], $screenshot["tmp_name"])) {
				return false;
			}
		}

		return true;
	}
}
