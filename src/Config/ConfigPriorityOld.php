<?php

namespace srag\Plugins\HelpMe\Config;

use ActiveRecord;
use arConnector;
use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ConfigPriorityOld
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @deprecated
 */
class ConfigPriorityOld extends ActiveRecord {

	use DICTrait;
	use HelpMeTrait;
	/**
	 * @var string
	 *
	 * @deprecated
	 */
	const TABLE_NAME = "ui_uihk_srsu_prio";
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
	public static function getConfigPriorities(): array {
		/**
		 * @var self[] $configPriorities
		 */

		$configPriorities = self::get();

		return $configPriorities;
	}


	/**
	 * @return array
	 *
	 * @deprecated
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
	 *
	 * @deprecated
	 */
	public static function setConfigPrioritiesArray(array $priorities)/*: void*/ {
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
	 *
	 * @deprecated
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 * @con_is_unique   true
	 *
	 * @deprecated
	 */
	protected $priority;


	/**
	 * ConfigPriorityOld constructor
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
	 * @return string
	 *
	 * @deprecated
	 */
	public function getPriority(): string {
		return $this->priority;
	}


	/**
	 * @param string $priority
	 *
	 * @deprecated
	 */
	public function setPriority(string $priority)/*: void*/ {
		$this->priority = $priority;
	}
}
