<?php

namespace srag\Plugins\HelpMe\Recipient;

use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Support\Support;

/**
 * Class RecipientCreateJiraTicket
 *
 * @package srag\Plugins\HelpMe\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecipientCreateJiraTicket extends Recipient {

	/**
	 * @var JiraCurl
	 */
	protected $jira_curl;
	/**
	 * @var string
	 */
	protected $issue_key;


	/**
	 * RecipientCreateJiraTicket constructor
	 *
	 * @param Support $support
	 */
	public function __construct(Support $support) {
		parent::__construct($support);

		$this->jira_curl = new JiraCurl();

		$this->jira_curl->setJiraDomain(Config::getField(Config::KEY_JIRA_DOMAIN));

		$this->jira_curl->setJiraAuthorization(Config::getField(Config::KEY_JIRA_AUTHORIZATION));

		$this->jira_curl->setJiraUsername(Config::getField(Config::KEY_JIRA_USERNAME));
		$this->jira_curl->setJiraPassword(Config::getField(Config::KEY_JIRA_PASSWORD));

		$this->jira_curl->setJiraConsumerKey(Config::getField(Config::KEY_JIRA_CONSUMER_KEY));
		$this->jira_curl->setJiraPrivateKey(Config::getField(Config::KEY_JIRA_PRIVATE_KEY));
		$this->jira_curl->setJiraAccessToken(Config::getField(Config::KEY_JIRA_ACCESS_TOKEN));
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
		$issue_key = $this->jira_curl->createJiraIssueTicket($this->support->getProject()
			->getProjectKey(), Config::getField(Config::KEY_JIRA_ISSUE_TYPE), $this->support->getSubject(), $this->support->getBody("jira"));

		if ($issue_key === NULL) {
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
			if (!$this->jira_curl->addAttachmentToIssue($this->issue_key, $screenshot->getName(), $screenshot->getMimeType(), $screenshot->getPath())) {
				return false;
			}
		}

		return true;
	}
}
