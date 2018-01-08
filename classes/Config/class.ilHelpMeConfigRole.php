<?php

require_once "Services/ActiveRecord/class.ActiveRecord.php";

/**
 * Config active record
 */
class ilHelpMeConfigRole extends ActiveRecord {

	const TABLE_NAME = "ui_uihk_srsu_roles";


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
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 * @con_is_unique   true
	 */
	protected $role_id;


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
	 * @return int
	 */
	public function getRoleId() {
		return $this->role_id;
	}


	/**
	 * @param int $role_id
	 */
	public function setRoleId($role_id) {
		$this->role_id = $role_id;
	}
}
