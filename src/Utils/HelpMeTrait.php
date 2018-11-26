<?php

namespace srag\Plugins\HelpMe\Utils;

use srag\Plugins\HelpMe\Access\Access;
use srag\Plugins\HelpMe\Access\Ilias;
use srag\Plugins\HelpMe\Project\Projects;

/**
 * Trait HelpMeTrait
 *
 * @package srag\Plugins\HelpMe\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait HelpMeTrait {

	/**
	 * @return Access
	 */
	protected static function access(): Access {
		return Access::getInstance();
	}


	/**
	 * @return Ilias
	 */
	protected static function ilias(): Ilias {
		return Ilias::getInstance();
	}


	/**
	 * @return Projects
	 */
	protected static function projects(): Projects {
		return Projects::getInstance();
	}
}
