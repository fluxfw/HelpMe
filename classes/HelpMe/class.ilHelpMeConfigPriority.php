<?php

require_once "Services/ActiveRecord/class.ActiveRecord.php";

/**
 * Config active record
 */
class ilHelpMeConfigPriority extends ActiveRecord {

	const TABLE_NAME = "ui_uihk_srsu_prio";


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
	 * @con_sequence    true
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 * @con_is_unique   true
	 */
	protected $priority;


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
	public function getPriority() {
		return $this->priority;
	}


	/**
	 * @param string $priority
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}
}
