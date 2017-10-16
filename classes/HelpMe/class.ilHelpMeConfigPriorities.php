<?php

require_once "Services/ActiveRecord/class.ActiveRecord.php";

/**
 * HelpMe active record
 */
class ilHelpMeConfigPriorities extends ActiveRecord {

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
	protected $priority_id;
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
	public function getPriorityId() {
		return $this->priority_id;
	}


	/**
	 * @param int $priority_id
	 */
	public function setPriorityId($priority_id) {
		$this->priority_id = $priority_id;
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
