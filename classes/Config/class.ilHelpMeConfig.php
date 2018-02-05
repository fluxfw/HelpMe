<?php

require_once "Services/ActiveRecord/class.ActiveRecord.php";

/**
 * Config active record
 */
class ilHelpMeConfig extends ActiveRecord {

	const TABLE_NAME = "ui_uihk_srsu_config";


	/**
	 * @return string
	 */
	static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return ilHelpMeConfig
	 */
	static function getConfig() {
		/**
		 * @var ilHelpMeConfig $config
		 */

		$config = self::get();
		if (count($config) > 0) {
			$config = $config[1];
		} else {
			$config = new self();
			$config->setId(1);
			$config->create();
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
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $recipient = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $send_email_address = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_domain = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_project_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_issue_type = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_authorization = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_username = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_password = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_consumer_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_private_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $jira_access_token = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $info = "";


	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}


	/**
	 * @return string
	 */
	public function getRecipient() {
		return $this->recipient;
	}


	/**
	 * @param string $recipient
	 */
	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}


	/**
	 * @return string
	 */
	public function getSendEmailAddress() {
		return $this->send_email_address;
	}


	/**
	 * @param string $send_email_address
	 */
	public function setSendEmailAddress($send_email_address) {
		$this->send_email_address = $send_email_address;
	}


	/**
	 * @return string
	 */
	public function getJiraDomain() {
		return $this->jira_domain;
	}


	/**
	 * @param string $jira_domain
	 */
	public function setJiraDomain($jira_domain) {
		$this->jira_domain = $jira_domain;
	}


	/**
	 * @return string
	 */
	public function getJiraProjectKey() {
		return $this->jira_project_key;
	}


	/**
	 * @param string $jira_project_key
	 */
	public function setJiraProjectKey($jira_project_key) {
		$this->jira_project_key = $jira_project_key;
	}


	/**
	 * @return string
	 */
	public function getJiraIssueType() {
		return $this->jira_issue_type;
	}


	/**
	 * @param string $jira_issue_type
	 */
	public function setJiraIssueType($jira_issue_type) {
		$this->jira_issue_type = $jira_issue_type;
	}


	/**
	 * @return string
	 */
	public function getJiraAuthorization() {
		return $this->jira_authorization;
	}


	/**
	 * @param string $jira_authorization
	 */
	public function setJiraAuthorization($jira_authorization) {
		$this->jira_authorization = $jira_authorization;
	}


	/**
	 * @return string
	 */
	public function getJiraUsername() {
		return $this->jira_username;
	}


	/**
	 * @param string $jira_username
	 */
	public function setJiraUsername($jira_username) {
		$this->jira_username = $jira_username;
	}


	/**
	 * @return string
	 */
	public function getJiraPassword() {
		return $this->jira_password;
	}


	/**
	 * @param string $jira_password
	 */
	public function setJiraPassword($jira_password) {
		$this->jira_password = $jira_password;
	}


	/**
	 * @return string
	 */
	public function getJiraConsumerKey() {
		return $this->jira_consumer_key;
	}


	/**
	 * @param string $jira_consumer_key
	 */
	public function setJiraConsumerKey($jira_consumer_key) {
		$this->jira_consumer_key = $jira_consumer_key;
	}


	/**
	 * @return string
	 */
	public function getJiraPrivateKey() {
		return $this->jira_private_key;
	}


	/**
	 * @param string $jira_private_key
	 */
	public function setJiraPrivateKey($jira_private_key) {
		$this->jira_private_key = $jira_private_key;
	}


	/**
	 * @return string
	 */
	public function getJiraAccessToken() {
		return $this->jira_access_token;
	}


	/**
	 * @param string $jira_access_token
	 */
	public function setJiraAccessToken($jira_access_token) {
		$this->jira_access_token = $jira_access_token;
	}


	/**
	 * @return string
	 */
	public function getInfo() {
		return $this->info;
	}


	/**
	 * @param string $info
	 */
	public function setInfo($info) {
		$this->info = $info;
	}
}
