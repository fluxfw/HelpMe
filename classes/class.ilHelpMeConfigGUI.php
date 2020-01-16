<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Project\ProjectsConfigGUI;
use srag\Plugins\HelpMe\Ticket\TicketsGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ilHelpMeConfigGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl: ilHelpMeConfigGUI
 */
class ilHelpMeConfigGUI extends ilPluginConfigGUI
{

    use DICTrait;
    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const CMD_CONFIGURE = "configure";
    const CMD_HIDE_USAGE = "hideUsage";
    const CMD_UPDATE_CONFIGURE = "updateConfigure";
    const LANG_MODULE = "config";
    const TAB_CONFIGURATION = "configuration";


    /**
     * ilHelpMeConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function performCommand(/*string*/ $cmd)/*:void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ProjectsConfigGUI::class);
                self::dic()->ctrl()->forwardCommand(new ProjectsConfigGUI());
                break;

            case strtolower(NotificationsCtrl::class);
                self::dic()->tabs()->activateTab(NotificationsCtrl::TAB_NOTIFICATIONS);
                self::dic()->ctrl()->forwardCommand(new NotificationsCtrl());
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CONFIGURE:
                    case self::CMD_HIDE_USAGE:
                    case self::CMD_UPDATE_CONFIGURE:
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
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->addTab(self::TAB_CONFIGURATION, self::plugin()->translate("configuration", self::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(self::class, self::CMD_CONFIGURE));

        ProjectsConfigGUI::addTabs();

        self::dic()->tabs()->addTab(NotificationsCtrl::TAB_NOTIFICATIONS, self::plugin()->translate("notifications", NotificationsCtrl::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(NotificationsCtrl::class, NotificationsCtrl::CMD_LIST_NOTIFICATIONS));

        self::dic()->locator()->addItem(ilHelpMePlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTarget($this, self::CMD_CONFIGURE));

        self::helpMe()->tickets()->showUsageConfigHint();
    }


    /**
     * @return ConfigFormGUI
     */
    protected function getConfigForm() : ConfigFormGUI
    {
        $form = new ConfigFormGUI($this);

        return $form;
    }


    /**
     *
     */
    protected function configure()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_CONFIGURATION);

        $form = $this->getConfigForm();

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateConfigure()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_CONFIGURATION);

        $form = $this->getConfigForm();

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("configuration_saved", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_CONFIGURE);
    }


    /**
     *
     */
    protected function hideUsage()/*: void*/
    {
        $usage_id = filter_input(INPUT_GET, TicketsGUI::GET_PARAM_USAGE_ID);

        if (!empty($usage_id)) {
            $usage_hidden = self::helpMe()->config()->getField(ConfigFormGUI::KEY_USAGE_HIDDEN);
            $usage_hidden[$usage_id] = true;
            self::helpMe()->config()->setField(ConfigFormGUI::KEY_USAGE_HIDDEN, $usage_hidden);

            ilUtil::sendSuccess(self::plugin()->translate("usage_hidden", self::LANG_MODULE), true);
        }

        self::dic()->ctrl()->redirectByClass(ilHelpMeConfigGUI::class);
    }
}
