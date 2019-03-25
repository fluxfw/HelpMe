<?php

namespace srag\Plugins\HelpMe\Config;

use ilHelpMePlugin;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfig;
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
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const KEY_JIRA_ISSUE_TYPE = "jira_issue_type";
	const KEY_JIRA_PASSWORD = "jira_password";
	const KEY_JIRA_PRIVATE_KEY = "jira_private_key";
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const KEY_JIRA_PROJECT_KEY = "jira_project_key";
	const KEY_JIRA_USERNAME = "jira_username";
	const KEY_PRIORITIES = "priorities";
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const KEY_PROJECTS = "projects";
	const KEY_RECIPIENT = "recipient";
	const KEY_RECIPIENT_TEMPLATES = "recipient_templates";
	const KEY_ROLES = "roles";
	const KEY_SEND_CONFIRMATION_EMAIL = "send_confirmation_email";
	const KEY_SEND_EMAIL_ADDRESS = "send_email_address";
	/**
	 * @var array
	 */
	protected static $fields = [
		self::KEY_INFO => self::TYPE_STRING,
		self::KEY_JIRA_ACCESS_TOKEN => self::TYPE_STRING,
		self::KEY_JIRA_AUTHORIZATION => self::TYPE_STRING,
		self::KEY_JIRA_CONSUMER_KEY => self::TYPE_STRING,
		self::KEY_JIRA_DOMAIN => self::TYPE_STRING,
		self::KEY_JIRA_ISSUE_TYPE => self::TYPE_STRING,
		self::KEY_JIRA_PASSWORD => self::TYPE_STRING,
		self::KEY_JIRA_PRIVATE_KEY => self::TYPE_STRING,
		self::KEY_JIRA_PROJECT_KEY => self::TYPE_STRING,
		self::KEY_JIRA_USERNAME => self::TYPE_STRING,
		self::KEY_PRIORITIES => [ self::TYPE_JSON, [] ],
		self::KEY_PROJECTS => [ self::TYPE_JSON, null, true ],
		self::KEY_RECIPIENT => self::TYPE_STRING,
		self::KEY_RECIPIENT_TEMPLATES => [ self::TYPE_JSON, [], true ],
		self::KEY_ROLES => [ self::TYPE_JSON, [] ],
		self::KEY_SEND_CONFIRMATION_EMAIL => [ self::TYPE_BOOLEAN, true ],
		self::KEY_SEND_EMAIL_ADDRESS => self::TYPE_STRING
	];
}
