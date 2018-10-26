<?php

namespace srag\Plugins\HelpMe\Config;

use ilEMailInputGUI;
use ilHelpMePlugin;
use ilMultiSelectInputGUI;
use ilPasswordInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\ActiveRecordConfig\ActiveRecordConfigFormGUI;
use srag\JiraCurl\JiraCurl;
use srag\Plugins\HelpMe\Recipient\Recipient;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	protected function initForm()/*: void*/ {
		parent::initForm();

		$configPriorities = Config::getPriorities();
		$allRoles = self::access()->getAllRoles();
		$configRoles = Config::getRoles();

		// Recipient
		$recipient = new ilRadioGroupInputGUI($this->txt(Config::KEY_RECIPIENT), Config::KEY_RECIPIENT);
		$recipient->setRequired(true);
		$recipient->setValue(Config::getRecipient());
		$this->addItem($recipient);

		// Send email
		$recipient_email = new ilRadioOption($this->txt("send_email"), Recipient::SEND_EMAIL);
		$recipient->addOption($recipient_email);

		$send_email_address = new ilEMailInputGUI($this->txt(Config::KEY_SEND_EMAIL_ADDRESS), Config::KEY_SEND_EMAIL_ADDRESS);
		$send_email_address->setRequired(true);
		$send_email_address->setValue(Config::getSendEmailAddress());
		$recipient_email->addSubItem($send_email_address);

		// Create Jira ticket
		$recipient_jira = new ilRadioOption($this->txt("create_jira_ticket"), Recipient::CREATE_JIRA_TICKET);
		$recipient->addOption($recipient_jira);

		$jira_domain = new ilTextInputGUI($this->txt(Config::KEY_JIRA_DOMAIN), Config::KEY_JIRA_DOMAIN);
		$jira_domain->setRequired(true);
		$jira_domain->setValue(Config::getJiraDomain());
		$recipient_jira->addSubItem($jira_domain);

		$jira_issue_type = new ilTextInputGUI($this->txt(Config::KEY_JIRA_ISSUE_TYPE), Config::KEY_JIRA_ISSUE_TYPE);
		$jira_issue_type->setRequired(true);
		$jira_issue_type->setInfo($this->txt(Config::KEY_JIRA_ISSUE_TYPE . "_info"));
		$jira_issue_type->setValue(Config::getJiraIssueType());
		$recipient_jira->addSubItem($jira_issue_type);

		// Jira authorization
		$jira_authorization = new ilRadioGroupInputGUI($this->txt(Config::KEY_JIRA_AUTHORIZATION), Config::KEY_JIRA_AUTHORIZATION);
		$jira_authorization->setRequired(true);
		$jira_authorization->setValue(Config::getJiraAuthorization());
		$recipient_jira->addSubItem($jira_authorization);

		// Username & Password
		$jira_authorization_userpassword = new ilRadioOption($this->txt("jira_usernamepassword"), JiraCurl::AUTHORIZATION_USERNAMEPASSWORD);
		$jira_authorization->addOption($jira_authorization_userpassword);

		$jira_username = new ilTextInputGUI($this->txt(Config::KEY_JIRA_USERNAME), Config::KEY_JIRA_USERNAME);
		$jira_username->setRequired(true);
		$jira_username->setValue(Config::getJiraUsername());
		$jira_authorization_userpassword->addSubItem($jira_username);

		$jira_password = new ilPasswordInputGUI($this->txt(Config::KEY_JIRA_PASSWORD), Config::KEY_JIRA_PASSWORD);
		$jira_password->setRequired(true);
		$jira_password->setRetype(false);
		$jira_password->setValue(Config::getJiraPassword());
		$jira_authorization_userpassword->addSubItem($jira_password);

		// oAuth
		$jira_oauth = new ilRadioOption($this->txt("jira_oauth"), JiraCurl::AUTHORIZATION_OAUTH);
		$jira_authorization->addOption($jira_oauth);

		$jira_consumer_key = new ilTextInputGUI($this->txt(Config::KEY_JIRA_CONSUMER_KEY), Config::KEY_JIRA_CONSUMER_KEY);
		$jira_consumer_key->setRequired(true);
		$jira_consumer_key->setValue(Config::getJiraConsumerKey());
		$jira_oauth->addSubItem($jira_consumer_key);

		$jira_private_key = new ilTextAreaInputGUI($this->txt(Config::KEY_JIRA_PRIVATE_KEY), Config::KEY_JIRA_PRIVATE_KEY);
		$jira_private_key->setRequired(true);
		$jira_private_key->setInfo($this->txt(Config::KEY_JIRA_PRIVATE_KEY . "_info"));
		$jira_private_key->setValue(Config::getJiraPrivateKey());
		$jira_oauth->addSubItem($jira_private_key);

		$jira_access_token = new ilTextInputGUI($this->txt(Config::KEY_JIRA_ACCESS_TOKEN), Config::KEY_JIRA_ACCESS_TOKEN);
		$jira_access_token->setRequired(true);
		$jira_access_token->setValue(Config::getJiraAccessToken());
		$jira_oauth->addSubItem($jira_access_token);

		// Priorities
		$priorities = new ilTextInputGUI($this->txt(Config::KEY_PRIORITIES), Config::KEY_PRIORITIES);
		$priorities->setMulti(true);
		$priorities->setRequired(true);
		$priorities->setValue($configPriorities);
		$this->addItem($priorities);

		// Info text
		$info = new ilTextAreaInputGUI($this->txt(Config::KEY_INFO), Config::KEY_INFO);
		$info->setRequired(true);
		$info->setUseRte(true);
		$info->setRteTagSet("extended");
		$info->setValue(Config::getInfo());
		$this->addItem($info);

		// Roles
		$roles = new ilMultiSelectInputGUI($this->txt(Config::KEY_ROLES), Config::KEY_ROLES);
		$roles->setRequired(true);
		$roles->setInfo($this->txt(Config::KEY_ROLES . "_info"));
		$roles->setOptions($allRoles);
		$roles->setValue($configRoles);
		$roles->enableSelectAll(true);
		$this->addItem($roles);
	}


	/**
	 * @inheritdoc
	 */
	public function updateConfig()/*: void*/ {
		$recipient = $this->getInput(Config::KEY_RECIPIENT);
		Config::setRecipient($recipient);

		$send_email_address = $this->getInput(Config::KEY_SEND_EMAIL_ADDRESS);
		Config::setSendEmailAddress($send_email_address);

		$jira_domain = $this->getInput(Config::KEY_JIRA_DOMAIN);
		Config::setJiraDomain($jira_domain ?? "");

		$jira_issue_type = $this->getInput(Config::KEY_JIRA_ISSUE_TYPE);
		Config::setJiraIssueType($jira_issue_type ?? "");

		$jira_authorization = $this->getInput(Config::KEY_JIRA_AUTHORIZATION);
		Config::setJiraAuthorization($jira_authorization ?? "");

		$jira_username = $this->getInput(Config::KEY_JIRA_USERNAME);
		Config::setJiraUsername($jira_username ?? "");

		$jira_password = $this->getInput(Config::KEY_JIRA_PASSWORD);
		Config::setJiraPassword($jira_password ?? "");

		$jira_consumer_key = $this->getInput(Config::KEY_JIRA_CONSUMER_KEY);
		Config::setJiraConsumerKey($jira_consumer_key ?? "");

		$jira_private_key = $this->getInput(Config::KEY_JIRA_PRIVATE_KEY);
		Config::setJiraPrivateKey($jira_private_key ?? "");

		$jira_access_token = $this->getInput(Config::KEY_JIRA_ACCESS_TOKEN);
		Config::setJiraAccessToken($jira_access_token ?? "");

		$priorities = $this->getInput(Config::KEY_PRIORITIES);
		Config::setPriorities($priorities);

		$info = $this->getInput(Config::KEY_INFO);
		Config::setInfo($info);

		$roles = $this->getInput(Config::KEY_ROLES);
		array_shift($roles);
		$roles = array_map(function (string $role_id): int {
			return intval($role_id);
		}, $roles);
		Config::setRoles($roles);
	}
}
