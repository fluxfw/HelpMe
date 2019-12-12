<?php

namespace srag\Plugins\HelpMe;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;
use srag\Plugins\HelpMe\Access\Ilias;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Project\Repository as ProjectRepository;
use srag\Plugins\HelpMe\Support\Repository as SupportRepository;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportField;
use srag\Plugins\HelpMe\Ticket\Repository as TicketRepository;
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
        $this->notifications4plugin()->withTableNamePrefix("ui_uihk_" . ilHelpMePlugin::PLUGIN_ID)->withPlugin(self::plugin())->withPlaceholderTypes([
            "support" => "object " . Support::class,
            "fields"  => "array " . SupportField::class
        ]);
    }


    /**
     * @return bool
     */
    public function currentUserHasRole() : bool
    {
        $user_id = self::helpMe()->ilias()->users()->getUserId();

        $user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
        $config_roles = Config::getField(Config::KEY_ROLES);

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
        self::dic()->database()->dropTable(Config::TABLE_NAME, false);
        $this->notifications4plugin()->dropTables();
        $this->project()->dropTables();
        $this->support()->dropTables();
        $this->ticket()->dropTables();
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
        Config::updateDB();
        $this->notifications4plugin()->installTables();
        $this->project()->installTables();
        $this->support()->installTables();
        $this->ticket()->installTables();
    }


    /**
     * @inheritDoc
     */
    public function notifications4plugin() : NotificationRepositoryInterface
    {
        return self::_notifications4plugin();
    }


    /**
     * @return ProjectRepository
     */
    public function project() : ProjectRepository
    {
        return ProjectRepository::getInstance();
    }


    /**
     * @return SupportRepository
     */
    public function support() : SupportRepository
    {
        return SupportRepository::getInstance();
    }


    /**
     * @return TicketRepository
     */
    public function ticket() : TicketRepository
    {
        return TicketRepository::getInstance();
    }
}
