<?php

namespace srag\Plugins\HelpMe\Access;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Users
 *
 * @package srag\Plugins\HelpMe\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Users {

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
	 * Users constructor
	 */
	private function __construct() {

	}


	/**
	 * @return int
	 */
	public function getUserId(): int {
		$user_id = self::dic()->user()->getId();

		// Fix login screen
		if ($user_id === 0 && boolval(self::dic()->settings()->get("pub_section"))) {
			$user_id = ANONYMOUS_USER_ID;
		}

		return intval($user_id);
	}
}
