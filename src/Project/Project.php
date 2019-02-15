<?php

namespace srag\Plugins\HelpMe\Project;

use ActiveRecord;
use arConnector;
use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Project
 *
 * @package srag\Plugins\HelpMe\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Project extends ActiveRecord {

	use DICTrait;
	use HelpMeTrait;
	const TABLE_NAME = "ui_uihk_srsu_project";
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
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 * @con_is_primary   true
	 * @con_sequence     true
	 */
	protected $project_id;
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $project_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $project_name = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $project_issue_type = "";


	/**
	 * Project constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, arConnector $connector = NULL) {
		parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|null
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
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "project_id":
				return intval($field_value);

			default:
				return NULL;
		}
	}


	/**
	 * @return int
	 */
	public function getProjectId(): int {
		return $this->project_id;
	}


	/**
	 * @param int $project_id
	 */
	public function setProjectId(int $project_id)/*: void*/ {
		$this->project_id = $project_id;
	}


	/**
	 * @return string
	 */
	public function getProjectKey(): string {
		return $this->project_key;
	}


	/**
	 * @param string $project_key
	 */
	public function setProjectKey(string $project_key)/*: void*/ {
		$this->project_key = $project_key;
	}


	/**
	 * @return string
	 */
	public function getProjectName(): string {
		return $this->project_name;
	}


	/**
	 * @param string $project_name
	 */
	public function setProjectName(string $project_name)/*: void*/ {
		$this->project_name = $project_name;
	}


	/**
	 * @return string
	 */
	public function getProjectIssueType(): string {
		return $this->project_issue_type;
	}


	/**
	 * @param string $project_issue_type
	 */
	public function setProjectIssueType(string $project_issue_type)/*: void*/ {
		$this->project_issue_type = $project_issue_type;
	}
}
