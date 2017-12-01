<?php

require_once "Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfig.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfigPriority.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfigRole.php";
require_once "Services/AccessControl/classes/class.ilObjRole.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/lib/BrowserDetector/vendor/autoload.php";

use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;

/**
 * HelpMe Plugin
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin {

	/**
	 * @var ilHelpMePlugin
	 */
	protected static $instance = NULL;


	/**
	 * @return ilHelpMePlugin
	 */
	static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	const ID = "srsu";
	/**
	 * @var ilDB
	 */
	protected $db;
	/**
	 * @var ilRbacReview
	 */
	protected $rbacreview;
	/**
	 * @var ilObjUser
	 */
	protected $usr;


	public function __construct() {
		parent::__construct();

		global $DIC;

		$this->db = $DIC->database();
		$this->rbacreview = $DIC->rbac()->review();
		$this->usr = $DIC->user();
	}


	function getPluginName() {
		return "HelpMe";
	}


	/**
	 * @return ilHelpMeConfig
	 */
	function getConfig() {
		/**
		 * @var ilHelpMeConfig $config
		 */

		$config = ilHelpMeConfig::get();
		if (sizeof($config) > 0) {
			$config = $config[1];
		} else {
			$config = new ilHelpMeConfig();
			$config->setId(1);
			$config->create();
		}

		return $config;
	}


	/**
	 * @return ilHelpMeConfigPriority[]
	 */
	function getConfigPriorities() {
		/**
		 * @var ilHelpMeConfigPriority[] $configPriorities
		 */

		$configPriorities = ilHelpMeConfigPriority::get();

		return $configPriorities;
	}


	/**
	 * @return array
	 */
	function getConfigPrioritiesArray() {
		$configPriorities = $this->getConfigPriorities();

		$priorities = [];
		foreach ($configPriorities as $configPriority) {
			$priorities[$configPriority->getId()] = $configPriority->getPriority();
		}

		return $priorities;
	}


	/**
	 * @param string[] $priorities
	 */
	function setConfigPrioritiesArray($priorities) {
		ilHelpMeConfigPriority::truncateDB();

		foreach ($priorities as $priority) {
			$configPriority = new ilHelpMeConfigPriority();
			$configPriority->setPriority($priority);
			$configPriority->create();
		}
	}


	/**
	 * @return ilHelpMeConfigRole[]
	 */
	function getConfigRoles() {
		/**
		 * @var ilHelpMeConfigRole[] $configRoles
		 */

		$configRoles = ilHelpMeConfigRole::get();

		return $configRoles;
	}


	/**
	 * @return array
	 */
	function getConfigRolesArray() {
		$configRoles = $this->getConfigRoles();

		$roles = [];
		foreach ($configRoles as $configRole) {
			$roles[$configRole->getId()] = $configRole->getRoleId();
		}

		return $roles;
	}


	/**
	 * @param int[] $roles
	 */
	function setConfigRolesArray($roles) {
		ilHelpMeConfigRole::truncateDB();

		foreach ($roles as $role_id) {
			if ($role_id !== "") { // fix select all
				$configRole = new ilHelpMeConfigRole();
				$configRole->setRoleId($role_id);
				$configRole->create();
			}
		}
	}


	/**
	 * @return array
	 */
	function getRoles() {
		/**
		 * @var array $global_roles
		 * @var array $roles
		 */

		$global_roles = $this->rbacreview->getRolesForIDs($this->rbacreview->getGlobalRoles(), false);

		$roles = [];
		foreach ($global_roles as $global_role) {
			$roles[$global_role["rol_id"]] = $global_role["title"];
		}

		return $roles;
	}


	/**
	 * @return bool
	 */
	function currentUserHasRole() {
		$user_id = $this->usr->getId();

		$user_roles = $this->rbacreview->getRolesByFilter(0, $user_id);
		$config_roles = $this->getConfigRolesArray();

		foreach ($user_roles as $user_role) {
			if (in_array($user_role["rol_id"], $config_roles)) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Get browser infos
	 *
	 * @return string "Browser Version / System Version"
	 */
	function getBrowserInfos() {
		$browser = new Browser();
		$os = new Os();

		$infos = $browser->getName() . (($browser->getVersion() !== Browser::UNKNOWN) ? " " . $browser->getVersion() : "") . " / " . $os->getName()
			. (($os->getVersion() !== Os::UNKNOWN) ? " " . $os->getVersion() : "");

		return $infos;
	}


	protected function beforeUninstall() {
		$this->db->dropTable(ilHelpMeConfig::TABLE_NAME, false);

		$this->db->dropTable(ilHelpMeConfigPriority::TABLE_NAME, false);

		$this->db->dropTable(ilHelpMeConfigRole::TABLE_NAME, false);

		return true;
	}
}
