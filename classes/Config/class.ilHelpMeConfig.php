<?php

use srag\ActiveRecordConfig\ActiveRecordConfig;

/**
 * Class ilHelpMeConfig
 */
class ilHelpMeConfig extends ActiveRecordConfig {

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
	const KEY_RECIPIENT = "recipient";
	const KEY_SEND_EMAIL_ADDRESS = "send_email_address";


	/**
	 * @return string
	 */
	public static function getInfo(): string {
		return self::getStringValue(self::KEY_INFO);
	}


	/**
	 * @param string $info
	 */
	public static function setInfo(string $info) {
		self::setStringValue(self::KEY_INFO, $info);
	}


	/**
	 * @return string
	 */
	public static function getJiraAccessToken(): string {
		return self::getStringValue(self::KEY_JIRA_ACCESS_TOKEN);
	}


	/**
	 * @param string $jira_access_token
	 */
	public static function setJiraAccessToken(string $jira_access_token) {
		self::setStringValue(self::KEY_JIRA_ACCESS_TOKEN, $jira_access_token);
	}


	/**
	 * @return string
	 */
	public static function getJiraAuthorization(): string {
		return self::getStringValue(self::KEY_JIRA_AUTHORIZATION);
	}


	/**
	 * @param string $jira_authorization
	 */
	public static function setJiraAuthorization(string $jira_authorization) {
		self::setStringValue(self::KEY_JIRA_AUTHORIZATION, $jira_authorization);
	}


	/**
	 * @return string
	 */
	public static function getJiraConsumerKey(): string {
		return self::getStringValue(self::KEY_JIRA_CONSUMER_KEY);
	}


	/**
	 * @param string $jira_consumer_key
	 */
	public static function setJiraConsumerKey(string $jira_consumer_key) {
		self::setStringValue(self::KEY_JIRA_CONSUMER_KEY, $jira_consumer_key);
	}


	/**
	 * @return string
	 */
	public static function getJiraDomain(): string {
		return self::getStringValue(self::KEY_JIRA_DOMAIN);
	}


	/**
	 * @param string $jira_domain
	 */
	public static function setJiraDomain(string $jira_domain) {
		self::setStringValue(self::KEY_JIRA_DOMAIN, $jira_domain);
	}


	/**
	 * @return string
	 */
	public static function getJiraIssueType(): string {
		return self::getStringValue(self::KEY_JIRA_ISSUE_TYPE);
	}


	/**
	 * @param string $jira_issue_type
	 */
	public static function setJiraIssueType(string $jira_issue_type) {
		self::setStringValue(self::KEY_JIRA_ISSUE_TYPE, $jira_issue_type);
	}


	/**
	 * @return string
	 */
	public static function getJiraPassword(): string {
		return self::getStringValue(self::KEY_JIRA_PASSWORD);
	}


	/**
	 * @param string $jira_password
	 */
	public static function setJiraPassword(string $jira_password) {
		self::setStringValue(self::KEY_JIRA_PASSWORD, $jira_password);
	}


	/**
	 * @return string
	 */
	public static function getJiraPrivateKey(): string {
		return self::getStringValue(self::KEY_JIRA_PRIVATE_KEY);
	}


	/**
	 * @param string $jira_private_key
	 */
	public static function setJiraPrivateKey(string $jira_private_key) {
		self::setStringValue(self::KEY_JIRA_PRIVATE_KEY, $jira_private_key);
	}


	/**
	 * @return string
	 */
	public static function getJiraProjectKey(): string {
		return self::getStringValue(self::KEY_JIRA_PROJECT_KEY);
	}


	/**
	 * @param string $jira_project_key
	 */
	public static function setJiraProjectKey(string $jira_project_key) {
		self::setStringValue(self::KEY_JIRA_PROJECT_KEY, $jira_project_key);
	}


	/**
	 * @return string
	 */
	public static function getJiraUsername(): string {
		return self::getStringValue(self::KEY_JIRA_USERNAME);
	}


	/**
	 * @param string $jira_username
	 */
	public static function setJiraUsername(string $jira_username) {
		self::setStringValue(self::KEY_JIRA_USERNAME, $jira_username);
	}


	/**
	 * @return string
	 */
	public static function getRecipient(): string {
		return self::getStringValue(self::KEY_RECIPIENT);
	}


	/**
	 * @param string $recipient
	 */
	public static function setRecipient(string $recipient) {
		self::setStringValue(self::KEY_RECIPIENT, $recipient);
	}


	/**
	 * @return string
	 */
	public static function getSendEmailAddress(): string {
		return self::getStringValue(self::KEY_SEND_EMAIL_ADDRESS);
	}


	/**
	 * @param string $send_email_address
	 */
	public static function setSendEmailAddress(string $send_email_address) {
		self::setStringValue(self::KEY_SEND_EMAIL_ADDRESS, $send_email_address);
	}
}
