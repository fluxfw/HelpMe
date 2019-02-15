<?php

namespace srag\Plugins\HelpMe\Project;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Projects
 *
 * @package srag\Plugins\HelpMe\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Projects {

	use DICTrait;
	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Projects constructor
	 */
	private function __construct() {

	}


	/**
	 * @return Project[]
	 */
	public function getProjects(): array {
		return Project::orderBy("project_name", "asc")->get();
	}


	/**
	 * @return array
	 */
	public function getProjectsArray(): array {
		return Project::orderBy("project_name", "asc")->getArray();
	}


	/**
	 * @param int $project_id
	 *
	 * @return Project|null
	 */
	public function getProjectById(int $project_id)/*: ?Project*/ {
		/**
		 * @var Project|null $project
		 */

		$project = Project::where([ "project_id" => $project_id ])->first();

		return $project;
	}


	/**
	 * @param string $project_key
	 *
	 * @return Project|null
	 */
	public function getProjectByKey(string $project_key)/*: ?Project*/ {
		/**
		 * @var Project|null $project
		 */

		$project = Project::where([ "project_key" => $project_key ])->first();

		return $project;
	}


	/**
	 * @return array
	 */
	public function getProjectsOptions(): array {
		return array_map(function (Project $project): string {
			return $project->getProjectName();
		}, $this->getProjects());
	}
}
