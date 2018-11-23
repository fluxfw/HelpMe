<?php

namespace srag\Plugins\HelpMe\Access;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Access
 *
 * @package srag\Plugins\HelpMe\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Access {

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
	 * Access constructor
	 */
	private function __construct() {

	}


	/**
	 * @return bool
	 */
	public function currentUserHasRole(): bool {
		$user_id = self::ilias()->users()->getUserId();

		$user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
		$config_roles = Config::getField(Config::KEY_ROLES);

		foreach ($user_roles as $user_role) {
			if (in_array($user_role, $config_roles)) {
				return true;
			}
		}

		return false;
	}
}
