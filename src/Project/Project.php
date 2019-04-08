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
	const DEFAULT_ISSUE_TYPE = "Support";
	//const DEFAULT_FIX_VERSION = "Backlog";
	const DEFAULT_FIX_VERSION = "";


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
	protected $project_url_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $project_name = "";
	/**
	 * @var array
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $project_issue_types = [ self::DEFAULT_ISSUE_TYPE ];
	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_is_notnull   true
	 */
	protected $project_fix_version = self::DEFAULT_FIX_VERSION;


	/**
	 * Project constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, arConnector $connector = null) {
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
			case "project_issue_types":
				return json_encode($field_value);

			default:
				return null;
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

			case "project_issue_types":
				return (array)json_decode($field_value);

			default:
				return null;
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
	public function getProjectUrlKey(): string {
		return $this->project_url_key;
	}


	/**
	 * @param string $project_url_key
	 */
	public function setProjectUrlKey(string $project_url_key)/*: void*/ {
		$this->project_url_key = $project_url_key;
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
	 * @return array
	 */
	public function getProjectIssueTypes(): array {
		return $this->project_issue_types;
	}


	/**
	 * @param array $project_issue_types
	 */
	public function setProjectIssueTypes(array $project_issue_types)/*: void*/ {
		$this->project_issue_types = $project_issue_types;
	}


	/**
	 * @return string
	 */
	public function getProjectFixVersion(): string {
		return $this->project_fix_version;
	}


	/**
	 * @param string $project_fix_version
	 */
	public function setProjectFixVersion(string $project_fix_version)/*: void*/ {
		$this->project_fix_version = $project_fix_version;
	}
}
