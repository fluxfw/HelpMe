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

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	protected function setForm()/*: void*/ {
		parent::setForm();

		$configPriorities = ConfigPriority::getConfigPrioritiesArray();
		$allRoles = ConfigRole::getAllRoles();
		$configRoles = ConfigRole::getConfigRolesArray();

		// Recipient
		$recipient = new ilRadioGroupInputGUI(self::plugin()->translate("srsu_recipient"), "srsu_recipient");
		$recipient->setRequired(true);
		$recipient->setValue(Config::getRecipient());
		$this->addItem($recipient);

		// Send email
		$recipient_email = new ilRadioOption(self::plugin()->translate("srsu_send_email"), Recipient::SEND_EMAIL);
		$recipient->addOption($recipient_email);

		$send_email_address = new ilEMailInputGUI(self::plugin()->translate("srsu_email_address"), "srsu_send_email_address");
		$send_email_address->setRequired(true);
		$send_email_address->setValue(Config::getSendEmailAddress());
		$recipient_email->addSubItem($send_email_address);

		// Create Jira ticket
		$recipient_jira = new ilRadioOption(self::plugin()->translate("srsu_create_jira_ticket"), Recipient::CREATE_JIRA_TICKET);
		$recipient->addOption($recipient_jira);

		$jira_domain = new ilTextInputGUI(self::plugin()->translate("srsu_jira_domain"), "srsu_jira_domain");
		$jira_domain->setRequired(true);
		$jira_domain->setValue(Config::getJiraDomain());
		$recipient_jira->addSubItem($jira_domain);

		$jira_project_key = new ilTextInputGUI(self::plugin()->translate("srsu_jira_project_key"), "srsu_jira_project_key");
		$jira_project_key->setRequired(true);
		$jira_project_key->setValue(Config::getJiraProjectKey());
		$recipient_jira->addSubItem($jira_project_key);

		$jira_issue_type = new ilTextInputGUI(self::plugin()->translate("srsu_jira_issue_type"), "srsu_jira_issue_type");
		$jira_issue_type->setRequired(true);
		$jira_issue_type->setInfo("Task, Bug, ...");
		$jira_issue_type->setValue(Config::getJiraIssueType());
		$recipient_jira->addSubItem($jira_issue_type);

		// Jira authorization
		$jira_authorization = new ilRadioGroupInputGUI(self::plugin()->translate("srsu_jira_authorization"), "srsu_jira_authorization");
		$jira_authorization->setRequired(true);
		$jira_authorization->setValue(Config::getJiraAuthorization());
		$recipient_jira->addSubItem($jira_authorization);

		// Username & Password
		$jira_authorization_userpassword = new ilRadioOption(self::plugin()
			->translate("srsu_jira_usernamepassword"), JiraCurl::AUTHORIZATION_USERNAMEPASSWORD);
		$jira_authorization->addOption($jira_authorization_userpassword);

		$jira_username = new ilTextInputGUI(self::plugin()->translate("srsu_jira_username"), "srsu_jira_username");
		$jira_username->setRequired(true);
		$jira_username->setValue(Config::getJiraUsername());
		$jira_authorization_userpassword->addSubItem($jira_username);

		$jira_password = new ilPasswordInputGUI(self::plugin()->translate("srsu_jira_password"), "srsu_jira_password");
		$jira_password->setRequired(true);
		$jira_password->setRetype(false);
		$jira_password->setValue(Config::getJiraPassword());
		$jira_authorization_userpassword->addSubItem($jira_password);

		// oAuth
		$jira_oauth = new ilRadioOption(self::plugin()->translate("srsu_jira_oauth"), JiraCurl::AUTHORIZATION_OAUTH);
		$jira_authorization->addOption($jira_oauth);

		$jira_consumer_key = new ilTextInputGUI(self::plugin()->translate("srsu_jira_consumer_key"), "srsu_jira_consumer_key");
		$jira_consumer_key->setRequired(true);
		$jira_consumer_key->setValue(Config::getJiraConsumerKey());
		$jira_oauth->addSubItem($jira_consumer_key);

		$jira_private_key = new ilTextAreaInputGUI(self::plugin()->translate("srsu_jira_private_key"), "srsu_jira_private_key");
		$jira_private_key->setRequired(true);
		$jira_private_key->setInfo("PEM formatted RSA private key");
		$jira_private_key->setValue(Config::getJiraPrivateKey());
		$jira_oauth->addSubItem($jira_private_key);

		$jira_access_token = new ilTextInputGUI(self::plugin()->translate("srsu_jira_access_token"), "srsu_jira_access_token");
		$jira_access_token->setRequired(true);
		$jira_access_token->setValue(Config::getJiraAccessToken());
		$jira_oauth->addSubItem($jira_access_token);

		// Priorities
		$priorities = new ilTextInputGUI(self::plugin()->translate("srsu_priorities"), "srsu_priorities");
		$priorities->setMulti(true);
		$priorities->setRequired(true);
		$priorities->setValue($configPriorities);
		$this->addItem($priorities);

		// Info text
		$info = new ilTextAreaInputGUI(self::plugin()->translate("srsu_info"), "srsu_info");
		$info->setRequired(true);
		$info->setUseRte(true);
		$info->setRteTagSet("extended");
		$info->setValue(Config::getInfo());
		$this->addItem($info);

		// Roles
		$roles = new ilMultiSelectInputGUI(self::plugin()->translate("srsu_roles"), "srsu_roles");
		$roles->setRequired(true);
		$roles->setInfo(self::plugin()->translate("srsu_roles_description"));
		$roles->setOptions($allRoles);
		$roles->setValue($configRoles);
		$roles->enableSelectAll(true);
		$this->addItem($roles);
	}


	/**
	 * @inheritdoc
	 */
	public function updateConfig()/*: void*/ {
		$recipient = $this->getInput("srsu_recipient");
		Config::setRecipient($recipient);

		$send_email_address = $this->getInput("srsu_send_email_address");
		Config::setSendEmailAddress($send_email_address);

		$jira_domain = $this->getInput("srsu_jira_domain");
		Config::setJiraDomain($jira_domain ?? "");

		$jira_project_key = $this->getInput("srsu_jira_project_key");
		Config::setJiraProjectKey($jira_project_key ?? "");

		$jira_issue_type = $this->getInput("srsu_jira_issue_type");
		Config::setJiraIssueType($jira_issue_type ?? "");

		$jira_authorization = $this->getInput("srsu_jira_authorization");
		Config::setJiraAuthorization($jira_authorization ?? "");

		$jira_username = $this->getInput("srsu_jira_username");
		Config::setJiraUsername($jira_username ?? "");

		$jira_password = $this->getInput("srsu_jira_password");
		Config::setJiraPassword($jira_password ?? "");

		$jira_consumer_key = $this->getInput("srsu_jira_consumer_key");
		Config::setJiraConsumerKey($jira_consumer_key ?? "");

		$jira_private_key = $this->getInput("srsu_jira_private_key");
		Config::setJiraPrivateKey($jira_private_key ?? "");

		$jira_access_token = $this->getInput("srsu_jira_access_token");
		Config::setJiraAccessToken($jira_access_token ?? "");

		$priorities = $this->getInput("srsu_priorities");
		ConfigPriority::setConfigPrioritiesArray($priorities);

		$info = $this->getInput("srsu_info");
		Config::setInfo($info);

		$roles = $this->getInput("srsu_roles");
		ConfigRole::setConfigRolesArray($roles);
	}
}
