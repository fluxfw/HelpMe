<?php

namespace srag\Plugins\HelpMe\Config;

use HelpMeRemoveDataConfirm;
use ilHelpMePlugin;
use srag\ActiveRecordConfig\ActiveRecordConfig;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Config
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig {

	use HelpMeTrait;
	const TABLE_NAME = "ui_uihk_srsu_config_n";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const KEY_INFO = "info";
	const KEY_JIRA_ACCESS_TOKEN = "jira_access_token";
	const KEY_JIRA_AUTHORIZATION = "jira_authorization";
	const KEY_JIRA_CONSUMER_KEY = "jira_consumer_key";
	const KEY_JIRA_DOMAIN = "jira_domain";
	const KEY_JIRA_ISSUE_TYPE = "jira_issue_type";
	const KEY_JIRA_PASSWORD = "jira_password";
	const KEY_JIRA_PRIVATE_KEY = "jira_private_key";
	const KEY_JIRA_PROJECT_KEY = "jira_project_key";
	const KEY_JIRA_USERNAME = "jira_username";
	const KEY_PRIORITIES = "priorities";
	const KEY_RECIPIENT = "recipient";
	const KEY_ROLES = "roles";
	const KEY_SEND_EMAIL_ADDRESS = "send_email_address";
	const DEFAULT_INFO = "";
	const DEFAULT_JIRA_ACCESS_TOKEN = "";
	const DEFAULT_JIRA_AUTHORIZATION = "";
	const DEFAULT_JIRA_CONSUMER_KEY = "";
	const DEFAULT_JIRA_DOMAIN = "";
	const DEFAULT_JIRA_ISSUE_TYPE = "";
	const DEFAULT_JIRA_PASSWORD = "";
	const DEFAULT_JIRA_PRIVATE_KEY = "";
	const DEFAULT_JIRA_PROJECT_KEY = "";
	const DEFAULT_ROLES = [];
	const DEFAULT_JIRA_USERNAME = "";
	const DEFAULT_PRIORITY = [];
	const DEFAULT_RECIPIENT = "";
	const DEFAULT_SEND_EMAIL_ADDRESS = "";


	/**
	 * @return string
	 */
	public static function getInfo(): string {
		return self::getStringValue(self::KEY_INFO, self::DEFAULT_INFO);
	}


	/**
	 * @param string $info
	 */
	public static function setInfo(string $info)/*: void*/ {
		self::setStringValue(self::KEY_INFO, $info);
	}


	/**
	 * @return string
	 */
	public static function getJiraAccessToken(): string {
		return self::getStringValue(self::KEY_JIRA_ACCESS_TOKEN, self::DEFAULT_JIRA_ACCESS_TOKEN);
	}


	/**
	 * @param string $jira_access_token
	 */
	public static function setJiraAccessToken(string $jira_access_token)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_ACCESS_TOKEN, $jira_access_token);
	}


	/**
	 * @return string
	 */
	public static function getJiraAuthorization(): string {
		return self::getStringValue(self::KEY_JIRA_AUTHORIZATION, self::DEFAULT_JIRA_AUTHORIZATION);
	}


	/**
	 * @param string $jira_authorization
	 */
	public static function setJiraAuthorization(string $jira_authorization)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_AUTHORIZATION, $jira_authorization);
	}


	/**
	 * @return string
	 */
	public static function getJiraConsumerKey(): string {
		return self::getStringValue(self::KEY_JIRA_CONSUMER_KEY, self::DEFAULT_JIRA_CONSUMER_KEY);
	}


	/**
	 * @param string $jira_consumer_key
	 */
	public static function setJiraConsumerKey(string $jira_consumer_key)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_CONSUMER_KEY, $jira_consumer_key);
	}


	/**
	 * @return string
	 */
	public static function getJiraDomain(): string {
		return self::getStringValue(self::KEY_JIRA_DOMAIN, self::DEFAULT_JIRA_DOMAIN);
	}


	/**
	 * @param string $jira_domain
	 */
	public static function setJiraDomain(string $jira_domain)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_DOMAIN, $jira_domain);
	}


	/**
	 * @return string
	 */
	public static function getJiraIssueType(): string {
		return self::getStringValue(self::KEY_JIRA_ISSUE_TYPE, self::DEFAULT_JIRA_ISSUE_TYPE);
	}


	/**
	 * @param string $jira_issue_type
	 */
	public static function setJiraIssueType(string $jira_issue_type)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_ISSUE_TYPE, $jira_issue_type);
	}


	/**
	 * @return string
	 */
	public static function getJiraPassword(): string {
		return self::getStringValue(self::KEY_JIRA_PASSWORD, self::DEFAULT_JIRA_PASSWORD);
	}


	/**
	 * @param string $jira_password
	 */
	public static function setJiraPassword(string $jira_password)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_PASSWORD, $jira_password);
	}


	/**
	 * @return string
	 */
	public static function getJiraPrivateKey(): string {
		return self::getStringValue(self::KEY_JIRA_PRIVATE_KEY, self::DEFAULT_JIRA_PRIVATE_KEY);
	}


	/**
	 * @param string $jira_private_key
	 */
	public static function setJiraPrivateKey(string $jira_private_key)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_PRIVATE_KEY, $jira_private_key);
	}


	/**
	 * @return string
	 */
	public static function getJiraProjectKey(): string {
		return self::getStringValue(self::KEY_JIRA_PROJECT_KEY, self::DEFAULT_JIRA_PROJECT_KEY);
	}


	/**
	 * @param string $jira_project_key
	 */
	public static function setJiraProjectKey(string $jira_project_key)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_PROJECT_KEY, $jira_project_key);
	}


	/**
	 * @return string
	 */
	public static function getJiraUsername(): string {
		return self::getStringValue(self::KEY_JIRA_USERNAME, self::DEFAULT_JIRA_USERNAME);
	}


	/**
	 * @param string $jira_username
	 */
	public static function setJiraUsername(string $jira_username)/*: void*/ {
		self::setStringValue(self::KEY_JIRA_USERNAME, $jira_username);
	}


	/**
	 * @return array
	 */
	public static function getPriorities(): array {
		return self::getJsonValue(self::KEY_PRIORITIES, true, self::DEFAULT_PRIORITY);
	}


	/**
	 * @param array $priorities
	 */
	public static function setPriorities(array $priorities)/*: void*/ {
		self::setJsonValue(self::KEY_PRIORITIES, $priorities);
	}


	/**
	 * @return string
	 */
	public static function getRecipient(): string {
		return self::getStringValue(self::KEY_RECIPIENT, self::DEFAULT_RECIPIENT);
	}


	/**
	 * @param string $recipient
	 */
	public static function setRecipient(string $recipient)/*: void*/ {
		self::setStringValue(self::KEY_RECIPIENT, $recipient);
	}


	/**
	 * @return array
	 */
	public static function getRoles(): array {
		return self::getJsonValue(self::KEY_ROLES, true, self::DEFAULT_ROLES);
	}


	/**
	 * @param array $roles
	 */
	public static function setRoles(array $roles)/*: void*/ {
		self::setJsonValue(self::KEY_ROLES, $roles);
	}


	/**
	 * @return string
	 */
	public static function getSendEmailAddress(): string {
		return self::getStringValue(self::KEY_SEND_EMAIL_ADDRESS, self::DEFAULT_SEND_EMAIL_ADDRESS);
	}


	/**
	 * @param string $send_email_address
	 */
	public static function setSendEmailAddress(string $send_email_address)/*: void*/ {
		self::setStringValue(self::KEY_SEND_EMAIL_ADDRESS, $send_email_address);
	}


	/**
	 * @return bool|null
	 */
	public static function getUninstallRemovesData()/*: ?bool*/ {
		return self::getXValue(HelpMeRemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, HelpMeRemoveDataConfirm::DEFAULT_UNINSTALL_REMOVES_DATA);
	}


	/**
	 * @param bool $uninstall_removes_data
	 */
	public static function setUninstallRemovesData(bool $uninstall_removes_data)/*: void*/ {
		self::setBooleanValue(HelpMeRemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA, $uninstall_removes_data);
	}


	/**
	 *
	 */
	public static function removeUninstallRemovesData()/*: void*/ {
		self::removeName(HelpMeRemoveDataConfirm::KEY_UNINSTALL_REMOVES_DATA);
	}
}
