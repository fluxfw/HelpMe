<?php

require_once "Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfig.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfigPriority.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfigRole.php";
require_once "Services/AccessControl/classes/class.ilObjRole.php";

/**
 * HelpMe Plugin
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin {

	/**
	 * @var ilHelpMePlugin
	 */
	protected static $cache;
	/**
	 * @var ilRbacReview
	 */
	protected $rbacreview;
	/**
	 * @var ilObjUser
	 */
	protected $usr;


	/**
	 * @return ilHelpMePlugin
	 */
	static function getInstance() {
		if (!isset(self::$cache)) {
			self::$cache = new self();
		}

		return self::$cache;
	}


	function getPluginName() {
		return "HelpMe";
	}


	public function __construct() {
		/**
		 * @var ilObjUser    $ilUser
		 * @var ilRbacReview $rbacreview
		 */

		parent::__construct();

		global $ilUser, $rbacreview;

		$this->rbacreview = $rbacreview;
		$this->usr = $ilUser;
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
			$config->setRecipient("send_email");
			$config->setSendEmailAddress("");
			$config->setInfo($this->txt("srsu_info"));
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

		$configPriorities = ilHelpMeConfigPriority::getArray();

		return $configPriorities;
	}


	/**
	 * @return string[]
	 */
	function getConfigPrioritiesArray() {
		/**
		 * @var string[] $priorities
		 */

		$priorities = array_map(function ($configPriority) {
			/**
			 * @var ilHelpMeConfigPriority $configPriority
			 */

			return $configPriority->getPriority();
		}, ilHelpMeConfigPriority::get());

		return $priorities;
	}


	/**
	 * @param string[] $priorities
	 */
	function setConfigPrioritiesArray($priorities) {
		ilHelpMeConfigPriority::truncateDB();

		foreach ($priorities as $priority) {
			/**
			 * @var string $priority
			 */

			$configPriority = new ilHelpMeConfigPriority();
			$configPriority->setPriority($priority);
			$configPriority->create();
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
	 * @return int[]
	 */
	function getConfigRolesArray() {
		/**
		 * @var int[] $roles
		 */

		$roles = array_map(function ($configRole) {
			/**
			 * @var ilHelpMeConfigRole $configRole
			 */

			return $configRole->getRoleId();
		}, ilHelpMeConfigRole::get());

		return $roles;
	}


	/**
	 * @param int[] $roles
	 */
	function setConfigRolesArray($roles) {
		ilHelpMeConfigRole::truncateDB();

		foreach ($roles as $role_id) {
			/**
			 * @var int $role_id
			 */

			if ($role_id !== "") { // fix select all
				$configRole = new ilHelpMeConfigRole();
				$configRole->setRoleId($role_id);
				$configRole->create();
			}
		}
	}


	/**
	 * @return bool
	 */
	function currentUserHasRole() {
		$user_id = $this->usr->getId();

		$user_roles = $this->rbacreview->getRolesByFilter(0, $user_id);
		$config_roles = $this->getConfigRolesArray();

		foreach ($user_roles as $user_role) {
			if (array_search($user_role["rol_id"], $config_roles) !== false) {
				return true;
			}
		}

		return false;
	}
}
