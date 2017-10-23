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
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $jira_project_type = 0;
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
	 * @return int
	 */
	public function getJiraProjectType() {
		return $this->jira_project_type;
	}


	/**
	 * @param int $jira_project_type
	 */
	public function setJiraProjectType($jira_project_type) {
		$this->jira_project_type = $jira_project_type;
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
