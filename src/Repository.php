<?php

namespace srag\Plugins\HelpMe;

use ilHelpMePlugin;
use srag\ActiveRecordConfig\HelpMe\Config\Config;
use srag\ActiveRecordConfig\HelpMe\Config\Repository as ConfigRepository;
use srag\ActiveRecordConfig\HelpMe\Utils\ConfigTrait;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\RepositoryInterface as Notifications4PluginRepositoryInterface;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;
use srag\Plugins\HelpMe\Access\Ilias;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Project\Repository as ProjectsRepository;
use srag\Plugins\HelpMe\Support\Repository as SupportRepository;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportField;
use srag\Plugins\HelpMe\Ticket\Repository as TicketsRepository;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\HelpMe
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use HelpMeTrait;
    use ConfigTrait {
        config as protected _config;
    }
    use Notifications4PluginTrait {
        notifications4plugin as protected _notifications4plugin;
    }
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Repository constructor
     */
    private function __construct()
    {
        $this->config()->withTableName("ui_uihk_" . ilHelpMePlugin::PLUGIN_ID . "_config_n")->withFields([
            ConfigFormGUI::KEY_INFO                                   => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_ACCESS_TOKEN                      => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_AUTHORIZATION                     => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_CONSUMER_KEY                      => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_DOMAIN                            => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST       => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_JIRA_PASSWORD                          => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_PRIVATE_KEY                       => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_CREATE_AS_CUSTOMER   => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_CREATE_NEW_CUSTOMERS => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_ID                   => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_LINK_TYPE            => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID      => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_JIRA_USERNAME                          => Config::TYPE_STRING,
            ConfigFormGUI::KEY_PAGE_REFERENCE                         => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_PRIORITIES                             => [Config::TYPE_JSON, []],
            ConfigFormGUI::KEY_RECIPIENT                              => Config::TYPE_STRING,
            ConfigFormGUI::KEY_RECIPIENT_TEMPLATES                    => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_ROLES                                  => [Config::TYPE_JSON, []],
            ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL                => [Config::TYPE_BOOLEAN, true],
            ConfigFormGUI::KEY_SEND_EMAIL_ADDRESS                     => Config::TYPE_STRING,
            ConfigFormGUI::KEY_USAGE_HIDDEN                           => [Config::TYPE_JSON, [], true]
        ]);

        $this->notifications4plugin()->withTableNamePrefix("ui_uihk_" . ilHelpMePlugin::PLUGIN_ID)->withPlugin(self::plugin())->withPlaceholderTypes([
            "support" => "object " . Support::class,
            "fields"  => "array " . SupportField::class
        ]);
    }


    /**
     * @inheritDoc
     */
    public function config() : ConfigRepository
    {
        return self::_config();
    }


    /**
     * @return bool
     */
    public function currentUserHasRole() : bool
    {
        $user_id = $this->ilias()->users()->getUserId();

        $user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
        $config_roles = self::helpMe()->config()->getField(ConfigFormGUI::KEY_ROLES);

        foreach ($user_roles as $user_role) {
            if (in_array($user_role, $config_roles)) {
                return true;
            }
        }

        return false;
    }


    /**
     *
     */
    public function dropTables()/*: void*/
    {
        $this->config()->dropTables();
        $this->notifications4plugin()->dropTables();
        $this->projects()->dropTables();
        $this->support()->dropTables();
        $this->tickets()->dropTables();
    }


    /**
     * @return Ilias
     */
    public function ilias() : Ilias
    {
        return Ilias::getInstance();
    }


    /**
     *
     */
    public function installTables()/*: void*/
    {
        $this->config()->installTables();
        $this->notifications4plugin()->installTables();
        $this->projects()->installTables();
        $this->support()->installTables();
        $this->tickets()->installTables();
    }


    /**
     * @inheritDoc
     */
    public function notifications4plugin() : Notifications4PluginRepositoryInterface
    {
        return self::_notifications4plugin();
    }


    /**
     * @return ProjectsRepository
     */
    public function projects() : ProjectsRepository
    {
        return ProjectsRepository::getInstance();
    }


    /**
     * @return SupportRepository
     */
    public function support() : SupportRepository
    {
        return SupportRepository::getInstance();
    }


    /**
     * @return TicketsRepository
     */
    public function tickets() : TicketsRepository
    {
        return TicketsRepository::getInstance();
    }
}
