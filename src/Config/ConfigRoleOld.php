<?php

namespace srag\Plugins\HelpMe\Config;

use ActiveRecord;
use arConnector;
use ilHelpMePlugin;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ConfigRoleOld
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated
 */
class ConfigRoleOld extends ActiveRecord {

	use DICTrait;
	use HelpMeTrait;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const TABLE_NAME = "ui_uihk_srsu_roles";
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
	 * @return self[]
	 *
	 * @deprecated
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
	 *
	 * @deprecated
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
	 *
	 * @deprecated
	 */
	public static function setConfigRolesArray(array $roles)/*: void*/ {
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
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 * @con_is_primary  true
	 * @con_sequence    true
	 *
	 * @deprecated
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
	 *
	 * @deprecated
	 */
	protected $role_id;


	/**
	 * ConfigRoleOld constructor
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
			case "role_id":
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
	 * @return int
	 *
	 * @deprecated
	 */
	public function getRoleId(): int {
		return $this->role_id;
	}


	/**
	 * @param int $role_id
	 *
	 * @deprecated
	 */
	public function setRoleId(int $role_id)/*: void*/ {
		$this->role_id = $role_id;
	}
}
