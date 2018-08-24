<?php

namespace srag\Plugins\HelpMe\Config;
use ActiveRecord;
use arConnector;
use ilHelpMePlugin;
use srag\DIC\DICTrait;

/**
 * Class HelpMeConfigRole
 *
 * @package srag\Plugins\HelpMe\Config
 */
class HelpMeConfigRole extends ActiveRecord {

	use DICTrait;
	const TABLE_NAME = "ui_uihk_srsu_roles";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @return string
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
	 * @return self[]
	 */
	public static function getConfigRoles(): array {
		/**
		 * @var self[] $configRoles
		 */

		$configRoles = self::get();

		return $configRoles;
	}


	/**
	 * @return array
	 */
	public static function getConfigRolesArray(): array {
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
	public static function setConfigRolesArray(array $roles) {
		self::truncateDB();

		foreach ($roles as $role_id) {
			if ($role_id !== "") { // fix select all
				$configRole = new self();
				$configRole->setRoleId($role_id);
				$configRole->store();
			}
		}
	}


	/**
	 * @return array
	 */
	public static function getAllRoles(): array {
		/**
		 * @var array $global_roles
		 * @var array $roles
		 */

		$global_roles = self::dic()->rbacreview()->getRolesForIDs(self::dic()->rbacreview()->getGlobalRoles(), false);

		$roles = [];
		foreach ($global_roles as $global_role) {
			$roles[$global_role["rol_id"]] = $global_role["title"];
		}

		return $roles;
	}


	/**
	 * @return bool
	 */
	public static function currentUserHasRole(): bool {
		$user_id = self::dic()->user()->getId();

		$user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
		$config_roles = self::getConfigRolesArray();

		foreach ($user_roles as $user_role) {
			if (in_array($user_role, $config_roles)) {
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
	 * @param string $field_name
	 *
	 * @return mixed|null
	 */
	public function sleep($field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			default:
				return NULL;
		}
	}


	/**
	 * HelpMeConfigRole constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct($primary_key_value = 0, arConnector $connector = NULL) {
		parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case "id":
			case "role_id":
				return intval($field_value);
				break;

			default:
				return NULL;
		}
	}


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
