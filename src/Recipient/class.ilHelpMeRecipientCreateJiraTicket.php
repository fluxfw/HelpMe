<?php

namespace srag\Plugins\HelpMe\Recipient;

use srag\Plugins\HelpMe\Config\ilHelpMeConfig;
use srag\Plugins\HelpMe\Jira\ilJiraCurl;
use srag\Plugins\HelpMe\Support\ilHelpMeSupport;

/**
 * Class ilHelpMeRecipientCreateJiraTicket
 *
 * @package srag\Plugins\HelpMe\Recipient
 */
class ilHelpMeRecipientCreateJiraTicket extends ilHelpMeRecipient {

	/**
	 * @var ilJiraCurl
	 */
	protected $jira_curl;
	/**
	 * @var string
	 */
	protected $issue_key;


	/**
	 * ilHelpMeRecipientCreateJiraTicket constructor
	 *
	 * @param ilHelpMeSupport $support
	 */
	public function __construct(ilHelpMeSupport $support) {
		parent::__construct($support);

		$this->jira_curl = new ilJiraCurl();

		$this->jira_curl->setJiraDomain(ilHelpMeConfig::getJiraDomain());

		$this->jira_curl->setJiraAuthorization(ilHelpMeConfig::getJiraAuthorization());

		$this->jira_curl->setJiraUsername(ilHelpMeConfig::getJiraUsername());
		$this->jira_curl->setJiraPassword(ilHelpMeConfig::getJiraPassword());

		$this->jira_curl->setJiraConsumerKey(ilHelpMeConfig::getJiraConsumerKey());
		$this->jira_curl->setJiraPrivateKey(ilHelpMeConfig::getJiraPrivateKey());
		$this->jira_curl->setJiraAccessToken(ilHelpMeConfig::getJiraAccessToken());
	}


	/**
	 * Send support to recipient
	 *
	 * @return bool
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
		$issue_key = $this->jira_curl->createJiraIssueTicket(ilHelpMeConfig::getJiraProjectKey(), ilHelpMeConfig::getJiraIssueType(), $this->support->getSubject(), $this->support->getBody("jira"));

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
