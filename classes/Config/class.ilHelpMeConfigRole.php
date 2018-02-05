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
	 * @return ilHelpMeConfigRole[]
	 */
	static function getConfigRoles() {
		/**
		 * @var ilHelpMeConfigRole[] $configRoles
		 */

		$configRoles = self::get();

		return $configRoles;
	}


	/**
	 * @return array
	 */
	static function getConfigRolesArray() {
		$configRoles = self::getConfigRoles();

		$roles = [];
		foreach ($configRoles as $configRole) {
			$roles[$configRole->getId()] = $configRole->getRoleId();
		}

		return $roles;
	}


	/**
	 * @param int[] $roles
	 */
	static function setConfigRolesArray($roles) {
		self::truncateDB();

		foreach ($roles as $role_id) {
			if ($role_id !== "") { // fix select all
				$configRole = new self();
				$configRole->setRoleId($role_id);
				$configRole->create();
			}
		}
	}


	/**
	 * @return array
	 */
	static function getAllRoles() {
		global $DIC;

		$rbacreview = $DIC->rbac()->review();

		/**
		 * @var array $global_roles
		 * @var array $roles
		 */

		$global_roles = $rbacreview->getRolesForIDs($rbacreview->getGlobalRoles(), false);

		$roles = [];
		foreach ($global_roles as $global_role) {
			$roles[$global_role["rol_id"]] = $global_role["title"];
		}

		return $roles;
	}


	/**
	 * @return bool
	 */
	static function currentUserHasRole() {
		global $DIC;

		$rbacreview = $DIC->rbac()->review();
		$user_id = $DIC->user()->getId();

		$user_roles = $rbacreview->getRolesByFilter(0, $user_id);
		$config_roles = self::getConfigRolesArray();

		foreach ($user_roles as $user_role) {
			if (in_array($user_role["rol_id"], $config_roles)) {
				return true;
			}
		}

		return false;
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
