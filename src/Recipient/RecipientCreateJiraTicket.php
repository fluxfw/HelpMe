<?php

namespace srag\Plugins\HelpMe\Recipient;

use ilCurlConnectionException;
use PHPMailer\PHPMailer\Exception as phpmailerException;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\Exception\DICException;
use srag\JiraCurl\HelpMe\Exception\JiraCurlException;
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
	 *
	 * @throws ActiveRecordConfigException
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
	 *
	 * @throws DICException
	 * @throws ilCurlConnectionException
	 * @throws JiraCurlException
	 * @throws phpmailerException
	 */
	public function sendSupportToRecipient()/*: void*/ {
		$this->createJiraTicket();

		$this->addScreenshoots();

		$this->sendConfirmationMail();
	}


	/**
	 * Create Jira ticket
	 *
	 * @throws ilCurlConnectionException
	 * @throws JiraCurlException
	 */
	protected function createJiraTicket()/*: void*/ {
		$issue_key = $this->jira_curl->createJiraIssueTicket($this->support->getProject()->getProjectKey(), $this->support->getProject()
			->getProjectIssueType(), $this->support->getSubject(), $this->support->getBody("jira"), $this->support->getProject()
			->getProjectFixVersion());

		$this->issue_key = $issue_key;
	}


	/**
	 * Add screenshots to Jira ticket
	 *
	 * @throws ilCurlConnectionException
	 * @throws JiraCurlException
	 */
	protected function addScreenshoots()/*: void*/ {
		foreach ($this->support->getScreenshots() as $screenshot) {
			$this->jira_curl->addAttachmentToIssue($this->issue_key, $screenshot->getName(), $screenshot->getMimeType(), $screenshot->getPath());
		}
	}
}
