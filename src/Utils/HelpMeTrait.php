<?php

namespace srag\Plugins\HelpMe\Utils;

use srag\Plugins\HelpMe\Access\Access;

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
}
