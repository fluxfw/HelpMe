<?php

/**
 * Config active record
 */
class ilHelpMeConfigPriority extends ActiveRecord {

	use srag\DIC\DIC;
	const TABLE_NAME = "ui_uihk_srsu_prio";


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return ilHelpMeConfigPriority[]
	 */
	public static function getConfigPriorities() {
		/**
		 * @var ilHelpMeConfigPriority[] $configPriorities
		 */

		$configPriorities = self::get();

		return $configPriorities;
	}


	/**
	 * @return array
	 */
	public static function getConfigPrioritiesArray() {
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
	public static function setConfigPrioritiesArray($priorities) {
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
