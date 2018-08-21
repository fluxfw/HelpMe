<?php

use srag\DIC\DICTrait;

/**
 * Class ilHelpMeConfigPriority
 */
class ilHelpMeConfigPriority extends ActiveRecord {

	use DICTrait;
	const TABLE_NAME = "ui_uihk_srsu_prio";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return self[]
	 */
	public static function getConfigPriorities(): array {
		/**
		 * @var self[] $configPriorities
		 */

		$configPriorities = self::get();

		return $configPriorities;
	}


	/**
	 * @return array
	 */
	public static function getConfigPrioritiesArray(): array {
		$configPriorities = self::getConfigPriorities();

		$priorities = [];
		foreach ($configPriorities as $configPriority) {
			$priorities[$configPriority->getId()] = $configPriority->getPriority();
		}

		return $priorities;
	}


	/**
	 * @param string[] $priorities
	 */
	public static function setConfigPrioritiesArray(array $priorities) {
		self::truncateDB();

		foreach ($priorities as $priority) {
			$configPriority = new self();
			$configPriority->setPriority($priority);
			$configPriority->store();
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
	 * ilHelpMeConfigPriority constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct($primary_key_value = 0, arConnector $connector = NULL) {
		parent::__construct($primary_key_value, $connector);
	}


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
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp($field_name, $field_value) {
		switch ($field_name) {
			case "id":
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
