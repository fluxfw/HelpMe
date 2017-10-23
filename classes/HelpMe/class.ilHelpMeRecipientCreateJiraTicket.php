<?php

require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeRecipient.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/JiraCurl/class.ilJiraCurl.php";

/**
 * Create Jira ticket
 */
class ilHelpMeRecipientCreateJiraTicket extends ilHelpMeRecipient {

	/**
	 * @var ilJiraCurl
	 */
	protected $jiraCurl;


	/**
	 * @param ilHelpMeSupport $support
	 * @param ilHelpMeConfig  $config
	 */
	function __construct($support, $config) {
		parent::__construct($support, $config);

		$this->jiraCurl = new ilJiraCurl();
		$this->jiraCurl->setJiraDomain($config->getJiraDomain());
		$this->jiraCurl->setJiraAuthorization($config->getJiraAuthorization());
		$this->jiraCurl->setJiraUsername($config->getJiraUsername());
		$this->jiraCurl->setJiraPassword($config->getJiraPassword());
		$this->jiraCurl->setJiraConsumerKey($config->getJiraConsumerKey());
	}


	/**
	 * Send support to recipient
	 *
	 * @return bool
	 */
	function sendSupportToRecipient() {
		return ($this->createJiraTicket() && $this->sendConfirmationMail());
	}


	/**
	 * Create Jira ticket
	 *
	 * @return bool
	 */
	protected function createJiraTicket() {
		return $this->jiraCurl->createJiraTicket($this->config->getJiraProjectKey(), $this->config->getJiraIssueType(), $this->support->getSubject(), $this->support->getBody());
	}
}
