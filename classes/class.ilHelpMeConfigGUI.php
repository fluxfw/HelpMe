<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl;
use srag\Plugins\HelpMe\Config\ConfigCtrl;
use srag\Plugins\HelpMe\Project\ProjectsConfigGUI;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;

/**
 * Class ilHelpMeConfigGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl: ilHelpMeConfigGUI
 * @ilCtrl_isCalledBy srag\RequiredData\HelpMe\Field\FieldsCtrl: ilHelpMeConfigGUI
 */
class ilHelpMeConfigGUI extends ilPluginConfigGUI
{

    use DICTrait;
    use HelpMeTrait;

    const CMD_CONFIGURE = "configure";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


    /**
     * ilHelpMeConfigGUI constructor
     */
    public function __construct()
    {
        self::helpMe();
    }


    /**
     * @inheritDoc
     */
    public function performCommand(/*string*/ $cmd) : void
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ConfigCtrl::class):
                self::dic()->ctrl()->forwardCommand(new ConfigCtrl());
                break;

            case strtolower(FieldsCtrl::class):
                self::dic()->ctrl()->forwardCommand(new FieldsCtrl(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG));
                break;

            case strtolower(ProjectsConfigGUI::class):
                self::dic()->ctrl()->forwardCommand(new ProjectsConfigGUI());
                break;

            case strtolower(NotificationsCtrl::class):
                self::dic()->tabs()->activateTab(NotificationsCtrl::TAB_NOTIFICATIONS);
                self::dic()->ctrl()->forwardCommand(new NotificationsCtrl());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CONFIGURE:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function configure() : void
    {
        self::dic()->ctrl()->redirectByClass(ConfigCtrl::class, ConfigCtrl::CMD_CONFIGURE);
    }


    /**
     *
     */
    protected function setTabs() : void
    {
        ConfigCtrl::addTabs();

        FieldsCtrl::addTabs();

        ProjectsConfigGUI::addTabs();

        self::dic()->tabs()->addTab(NotificationsCtrl::TAB_NOTIFICATIONS, self::plugin()->translate("notifications", NotificationsCtrl::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(NotificationsCtrl::class, NotificationsCtrl::CMD_LIST_NOTIFICATIONS));

        self::dic()->locator()->addItem(ilHelpMePlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTarget($this, self::CMD_CONFIGURE));

        self::helpMe()->tickets()->showUsageConfigHint();
    }
}
