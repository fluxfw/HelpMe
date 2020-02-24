<?php

namespace srag\Plugins\HelpMe;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\RepositoryInterface as Notifications4PluginRepositoryInterface;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;
use srag\Plugins\HelpMe\Access\Ilias;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Config\Repository as ConfigRepository;
use srag\Plugins\HelpMe\Job\Repository as JobsRepository;
use srag\Plugins\HelpMe\Project\Repository as ProjectsRepository;
use srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime\CreatedDateTimeField;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\IssueTypeField;
use srag\Plugins\HelpMe\RequiredData\Field\Login\LoginField;
use srag\Plugins\HelpMe\RequiredData\Field\PageReference\PageReferenceField;
use srag\Plugins\HelpMe\RequiredData\Field\Project\ProjectField;
use srag\Plugins\HelpMe\RequiredData\Field\Screenshots\ScreenshotsField;
use srag\Plugins\HelpMe\RequiredData\Field\SystemInfos\SystemInfosField;
use srag\Plugins\HelpMe\Support\Repository as SupportRepository;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportField;
use srag\Plugins\HelpMe\Ticket\Repository as TicketsRepository;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Repository as RequiredDataRepository;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

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
    use RequiredDataTrait {
        requiredData as protected _requiredData;
    }
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var self|null
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

        $this->requiredData()->withTableNamePrefix(ilHelpMePlugin::PLUGIN_ID)->withPlugin(self::plugin())->withEnableNames(true);
        $this->requiredData()->fields()->factory()->addClass(CreatedDateTimeField::class);
        $this->requiredData()->fields()->factory()->addClass(IssueTypeField::class);
        $this->requiredData()->fields()->factory()->addClass(LoginField::class);
        $this->requiredData()->fields()->factory()->addClass(PageReferenceField::class);
        $this->requiredData()->fields()->factory()->addClass(ProjectField::class);
        $this->requiredData()->fields()->factory()->addClass(ScreenshotsField::class);
        $this->requiredData()->fields()->factory()->addClass(SystemInfosField::class);
    }


    /**
     * @return ConfigRepository
     */
    public function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }


    /**
     * @return bool
     */
    public function currentUserHasRole() : bool
    {
        $user_id = $this->ilias()->users()->getUserId();

        $user_roles = self::dic()->rbacreview()->assignedGlobalRoles($user_id);
        $config_roles = self::helpMe()->config()->getValue(ConfigFormGUI::KEY_ROLES);

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
        $this->jobs()->dropTables();
        $this->notifications4plugin()->dropTables();
        $this->projects()->dropTables();
        $this->requiredData()->dropTables();
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
        $this->jobs()->installTables();
        $this->notifications4plugin()->installTables();
        $this->projects()->installTables();
        $this->requiredData()->installTables();
        $this->support()->installTables();
        $this->tickets()->installTables();
    }


    /**
     * @return JobsRepository
     */
    public function jobs() : JobsRepository
    {
        return JobsRepository::getInstance();
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
     * @inheritDoc
     */
    public function requiredData() : RequiredDataRepository
    {
        return self::_requiredData();
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
