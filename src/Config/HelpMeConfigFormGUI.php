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
use srag\Plugins\HelpMe\Recipient\HelpMeRecipient;

/**
 * Class HelpMeConfigFormGUI
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag <support-custom1@studer-raimann.ch>
 */
class HelpMeConfigFormGUI extends ActiveRecordConfigFormGUI {

	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	protected function setForm()/*: void*/ {
		parent::setForm();

		$configPriorities = HelpMeConfigPriority::getConfigPrioritiesArray();
		$allRoles = HelpMeConfigRole::getAllRoles();
		$configRoles = HelpMeConfigRole::getConfigRolesArray();

		// Recipient
		$recipient = new ilRadioGroupInputGUI(self::plugin()->translate("srsu_recipient"), "srsu_recipient");
		$recipient->setRequired(true);
		$recipient->setValue(HelpMeConfig::getRecipient());
		$this->addItem($recipient);

		// Send email
		$recipient_email = new ilRadioOption(self::plugin()->translate("srsu_send_email"), HelpMeRecipient::SEND_EMAIL);
		$recipient->addOption($recipient_email);

		$send_email_address = new ilEMailInputGUI(self::plugin()->translate("srsu_email_address"), "srsu_send_email_address");
		$send_email_address->setRequired(true);
		$send_email_address->setValue(HelpMeConfig::getSendEmailAddress());
		$recipient_email->addSubItem($send_email_address);

		// Create Jira ticket
		$recipient_jira = new ilRadioOption(self::plugin()->translate("srsu_create_jira_ticket"), HelpMeRecipient::CREATE_JIRA_TICKET);
		$recipient->addOption($recipient_jira);

		$jira_domain = new ilTextInputGUI(self::plugin()->translate("srsu_jira_domain"), "srsu_jira_domain");
		$jira_domain->setRequired(true);
		$jira_domain->setValue(HelpMeConfig::getJiraDomain());
		$recipient_jira->addSubItem($jira_domain);

		$jira_project_key = new ilTextInputGUI(self::plugin()->translate("srsu_jira_project_key"), "srsu_jira_project_key");
		$jira_project_key->setRequired(true);
		$jira_project_key->setValue(HelpMeConfig::getJiraProjectKey());
		$recipient_jira->addSubItem($jira_project_key);

		$jira_issue_type = new ilTextInputGUI(self::plugin()->translate("srsu_jira_issue_type"), "srsu_jira_issue_type");
		$jira_issue_type->setRequired(true);
		$jira_issue_type->setInfo("Task, Bug, ...");
		$jira_issue_type->setValue(HelpMeConfig::getJiraIssueType());
		$recipient_jira->addSubItem($jira_issue_type);

		// Jira authorization
		$jira_authorization = new ilRadioGroupInputGUI(self::plugin()->translate("srsu_jira_authorization"), "srsu_jira_authorization");
		$jira_authorization->setRequired(true);
		$jira_authorization->setValue(HelpMeConfig::getJiraAuthorization());
		$recipient_jira->addSubItem($jira_authorization);

		// Username & Password
		$jira_authorization_userpassword = new ilRadioOption(self::plugin()
			->translate("srsu_jira_usernamepassword"), JiraCurl::AUTHORIZATION_USERNAMEPASSWORD);
		$jira_authorization->addOption($jira_authorization_userpassword);

		$jira_username = new ilTextInputGUI(self::plugin()->translate("srsu_jira_username"), "srsu_jira_username");
		$jira_username->setRequired(true);
		$jira_username->setValue(HelpMeConfig::getJiraUsername());
		$jira_authorization_userpassword->addSubItem($jira_username);

		$jira_password = new ilPasswordInputGUI(self::plugin()->translate("srsu_jira_password"), "srsu_jira_password");
		$jira_password->setRequired(true);
		$jira_password->setRetype(false);
		$jira_password->setValue(HelpMeConfig::getJiraPassword());
		$jira_authorization_userpassword->addSubItem($jira_password);

		// oAuth
		$jira_oauth = new ilRadioOption(self::plugin()->translate("srsu_jira_oauth"), JiraCurl::AUTHORIZATION_OAUTH);
		$jira_authorization->addOption($jira_oauth);

		$jira_consumer_key = new ilTextInputGUI(self::plugin()->translate("srsu_jira_consumer_key"), "srsu_jira_consumer_key");
		$jira_consumer_key->setRequired(true);
		$jira_consumer_key->setValue(HelpMeConfig::getJiraConsumerKey());
		$jira_oauth->addSubItem($jira_consumer_key);

		$jira_private_key = new ilTextAreaInputGUI(self::plugin()->translate("srsu_jira_private_key"), "srsu_jira_private_key");
		$jira_private_key->setRequired(true);
		$jira_private_key->setInfo("PEM formatted RSA private key");
		$jira_private_key->setValue(HelpMeConfig::getJiraPrivateKey());
		$jira_oauth->addSubItem($jira_private_key);

		$jira_access_token = new ilTextInputGUI(self::plugin()->translate("srsu_jira_access_token"), "srsu_jira_access_token");
		$jira_access_token->setRequired(true);
		$jira_access_token->setValue(HelpMeConfig::getJiraAccessToken());
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
		$info->setValue(HelpMeConfig::getInfo());
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
		HelpMeConfig::setRecipient($recipient);

		$send_email_address = $this->getInput("srsu_send_email_address");
		HelpMeConfig::setSendEmailAddress($send_email_address);

		$jira_domain = $this->getInput("srsu_jira_domain");
		HelpMeConfig::setJiraDomain($jira_domain ?? "");

		$jira_project_key = $this->getInput("srsu_jira_project_key");
		HelpMeConfig::setJiraProjectKey($jira_project_key ?? "");

		$jira_issue_type = $this->getInput("srsu_jira_issue_type");
		HelpMeConfig::setJiraIssueType($jira_issue_type ?? "");

		$jira_authorization = $this->getInput("srsu_jira_authorization");
		HelpMeConfig::setJiraAuthorization($jira_authorization ?? "");

		$jira_username = $this->getInput("srsu_jira_username");
		HelpMeConfig::setJiraUsername($jira_username ?? "");

		$jira_password = $this->getInput("srsu_jira_password");
		HelpMeConfig::setJiraPassword($jira_password ?? "");

		$jira_consumer_key = $this->getInput("srsu_jira_consumer_key");
		HelpMeConfig::setJiraConsumerKey($jira_consumer_key ?? "");

		$jira_private_key = $this->getInput("srsu_jira_private_key");
		HelpMeConfig::setJiraPrivateKey($jira_private_key ?? "");

		$jira_access_token = $this->getInput("srsu_jira_access_token");
		HelpMeConfig::setJiraAccessToken($jira_access_token ?? "");

		$priorities = $this->getInput("srsu_priorities");
		HelpMeConfigPriority::setConfigPrioritiesArray($priorities);

		$info = $this->getInput("srsu_info");
		HelpMeConfig::setInfo($info);

		$roles = $this->getInput("srsu_roles");
		HelpMeConfigRole::setConfigRolesArray($roles);
	}
}
