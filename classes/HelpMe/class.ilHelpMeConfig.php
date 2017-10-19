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
	protected $recipient;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  false
	 */
	protected $send_email_address;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $info;


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
