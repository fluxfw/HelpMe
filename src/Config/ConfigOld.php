<?php

namespace srag\Plugins\HelpMe\Config;

use ActiveRecord;
use arConnector;
use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ConfigOld
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated
 */
class ConfigOld extends ActiveRecord {

	use DICTrait;
	use HelpMeTrait;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const TABLE_NAME = "ui_uihk_srsu_config";
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getConnectorContainerName(): string {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName(): string {
		return self::TABLE_NAME;
	}


	/**
	 * @return self
	 *
	 * @deprecated
	 */
	public static function getConfig(): self {
		/**
		 * @var self $config
		 */

		$config = self::get();
		if (count($config) > 0) {
			$config = $config[1];
		} else {
			$config = new self();
			$config->setId(1);
			$config->store();
		}

		return $config;
	}


	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 * @con_is_primary  true
	 *
	 * @deprecated
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $recipient = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $send_email_address = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_domain = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_project_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_issue_type = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_authorization = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_username = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_password = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_consumer_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_private_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $jira_access_token = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 *
	 * @deprecated
	 */
	protected $info = "";


	/**
	 * ConfigOld constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 *
	 * @deprecated
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, /*?*/
		arConnector $connector = NULL) {
		parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|null
	 *
	 * @deprecated
	 */
	public function sleep(/*string*/
		$field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			default:
				return NULL;
		}
	}


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 *
	 * @deprecated
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "id":
				return intval($field_value);
				break;

			default:
				return NULL;
		}
	}


	/**
	 * @return int
	 *
	 * @deprecated
	 */
	public function getId(): int {
		return $this->id;
	}


	/**
	 * @param int $id
	 *
	 * @deprecated
	 */
	public function setId(int $id)/*: void*/ {
		$this->id = $id;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getRecipient(): string {
		return $this->recipient;
	}


	/**
	 * @param string $recipient
	 *
	 * @deprecated
	 */
	public function setRecipient(string $recipient)/*: void*/ {
		$this->recipient = $recipient;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getSendEmailAddress(): string {
		return $this->send_email_address;
	}


	/**
	 * @param string $send_email_address
	 *
	 * @deprecated
	 */
	public function setSendEmailAddress(string $send_email_address)/*: void*/ {
		$this->send_email_address = $send_email_address;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraDomain(): string {
		return $this->jira_domain;
	}


	/**
	 * @param string $jira_domain
	 *
	 * @deprecated
	 */
	public function setJiraDomain(string $jira_domain)/*: void*/ {
		$this->jira_domain = $jira_domain;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraProjectKey(): string {
		return $this->jira_project_key;
	}


	/**
	 * @param string $jira_project_key
	 *
	 * @deprecated
	 */
	public function setJiraProjectKey(string $jira_project_key)/*: void*/ {
		$this->jira_project_key = $jira_project_key;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraIssueType(): string {
		return $this->jira_issue_type;
	}


	/**
	 * @param string $jira_issue_type
	 *
	 * @deprecated
	 */
	public function setJiraIssueType(string $jira_issue_type)/*: void*/ {
		$this->jira_issue_type = $jira_issue_type;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraAuthorization(): string {
		return $this->jira_authorization;
	}


	/**
	 * @param string $jira_authorization
	 *
	 * @deprecated
	 */
	public function setJiraAuthorization(string $jira_authorization)/*: void*/ {
		$this->jira_authorization = $jira_authorization;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraUsername(): string {
		return $this->jira_username;
	}


	/**
	 * @param string $jira_username
	 *
	 * @deprecated
	 */
	public function setJiraUsername(string $jira_username)/*: void*/ {
		$this->jira_username = $jira_username;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraPassword(): string {
		return $this->jira_password;
	}


	/**
	 * @param string $jira_password
	 *
	 * @deprecated
	 */
	public function setJiraPassword(string $jira_password)/*: void*/ {
		$this->jira_password = $jira_password;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraConsumerKey(): string {
		return $this->jira_consumer_key;
	}


	/**
	 * @param string $jira_consumer_key
	 *
	 * @deprecated
	 */
	public function setJiraConsumerKey(string $jira_consumer_key)/*: void*/ {
		$this->jira_consumer_key = $jira_consumer_key;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraPrivateKey(): string {
		return $this->jira_private_key;
	}


	/**
	 * @param string $jira_private_key
	 *
	 * @deprecated
	 */
	public function setJiraPrivateKey(string $jira_private_key)/*: void*/ {
		$this->jira_private_key = $jira_private_key;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getJiraAccessToken(): string {
		return $this->jira_access_token;
	}


	/**
	 * @param string $jira_access_token
	 *
	 * @deprecated
	 */
	public function setJiraAccessToken(string $jira_access_token)/*: void*/ {
		$this->jira_access_token = $jira_access_token;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function getInfo(): string {
		return $this->info;
	}


	/**
	 * @param string $info
	 *
	 * @deprecated
	 */
	public function setInfo(string $info)/*: void*/ {
		$this->info = $info;
	}
}
