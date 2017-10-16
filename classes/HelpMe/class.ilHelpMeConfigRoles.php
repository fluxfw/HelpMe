<?php

require_once "Services/ActiveRecord/class.ActiveRecord.php";

/**
 * HelpMe active record
 */
class ilHelpMeConfigRoles extends ActiveRecord {

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
	 */
	protected $role_id;


	/**
	 * @return int
	 */
	public function getRoleId() {
		return $this->role_id;
	}
}
