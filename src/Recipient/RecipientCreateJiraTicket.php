<?php

namespace srag\Plugins\HelpMe\Recipient;

use ilCurlConnectionException;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\Exception\DICException;
use srag\JiraCurl\HelpMe\Exception\JiraCurlException;
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Notifications4Plugin\HelpMe\Exception\Notifications4PluginException;
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
	protected $ticket_key = "";
	/**
	 * @var string
	 */
	protected $ticket_title = "";


	/**
	 * RecipientCreateJiraTicket constructor
	 *
	 * @param Support $support
	 *
	 * @throws ActiveRecordConfigException
	 */
	public function __construct(Support $support) {
		parent::__construct($support);

		$this->jira_curl = self::supports()->initJiraCurl();
	}


	/**
	 * @inheritdoc
	 *
	 * @throws ilCurlConnectionException
	 * @throws JiraCurlException
	 */
	public function sendSupportToRecipient()/*: void*/ {
		$this->createJiraTicket();

		$this->addScreenshoots();

		$this->sendConfirmationMail();

		if (self::tickets()->isEnabled()) {
			$ticket = self::tickets()->factory()->fromSupport($this->support, $this->ticket_key, $this->ticket_title);

			self::tickets()->storeInstance($ticket);
		}
	}


	/**
	 * Create Jira ticket
	 *
	 * @throws ActiveRecordConfigException
	 * @throws DICException
	 * @throws ilCurlConnectionException
	 * @throws JiraCurlException
	 * @throws Notifications4PluginException
	 */
	protected function createJiraTicket()/*: void*/ {
		$this->ticket_title = $this->getSubject(self::CREATE_JIRA_TICKET);

		$this->ticket_key = $this->jira_curl->createJiraIssueTicket($this->support->getProject()
			->getProjectKey(), $this->support->getIssueType(), $this->ticket_title, $this->getBody(self::CREATE_JIRA_TICKET), $this->support->getPriority(), $this->support->getFixVersion());
	}


	/**
	 * Add screenshots to Jira ticket
	 *
	 * @throws ilCurlConnectionException
	 * @throws JiraCurlException
	 */
	protected function addScreenshoots()/*: void*/ {
		foreach ($this->support->getScreenshots() as $screenshot) {
			$this->jira_curl->addAttachmentToIssue($this->ticket_key, $screenshot->getName(), $screenshot->getMimeType(), $screenshot->getPath());
		}
	}
}
