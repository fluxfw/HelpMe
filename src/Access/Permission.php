<?php

namespace srag\Plugins\HelpMe\Access;

use ilHelpMePlugin;
use srag\DIC\DICTrait;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Permission
 *
 * @package srag\Plugins\HelpMe\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Permission {

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
	 * Permission constructor
	 */
	private function __construct() {

	}


	/**
	 * @return bool
	 */
	public function currentUserHasRole(): bool {
		$user_id = self::dic()->user()->getId();

		// Fix login screen
		if ($user_id === 0 && boolval(self::dic()->settings()->get("pub_section"))) {
			$user_id = ANONYMOUS_USER_ID;
		}

		$user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
		$config_roles = Config::getRoles();

		foreach ($user_roles as $user_role) {
			if (in_array($user_role, $config_roles)) {
				return true;
			}
		}

		return false;
	}
}
