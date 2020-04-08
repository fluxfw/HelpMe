<?php

namespace srag\Plugins\HelpMe\Config;

use ilHelpMePlugin;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Ticket\TicketsGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ConfigCtrl
 *
 * @package           srag\Plugins\HelpMe\Config
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Config\ConfigCtrl: ilHelpMeConfigGUI
 */
class ConfigCtrl
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
     * ConfigCtrl constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*:void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
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
    public static function addTabs()/*: void*/
    {
        self::dic()->tabs()->addTab(self::TAB_CONFIGURATION, self::plugin()->translate("configuration", self::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTargetByClass(self::class, self::CMD_CONFIGURE));
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {

    }


    /**
     *
     */
    protected function configure()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_CONFIGURATION);

        $form = self::helpMe()->config()->factory()->newFormInstance($this);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateConfigure()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_CONFIGURATION);

        $form = self::helpMe()->config()->factory()->newFormInstance($this);

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
        $usage_id = strval(filter_input(INPUT_GET, TicketsGUI::GET_PARAM_USAGE_ID));

        if (!empty($usage_id)) {
            $usage_hidden = self::helpMe()->config()->getValue(ConfigFormGUI::KEY_USAGE_HIDDEN);
            $usage_hidden[$usage_id] = true;
            self::helpMe()->config()->setValue(ConfigFormGUI::KEY_USAGE_HIDDEN, $usage_hidden);

            ilUtil::sendSuccess(self::plugin()->translate("usage_hidden", self::LANG_MODULE), true);
        }

        self::dic()->ctrl()->redirectByClass(self::class, self::CMD_CONFIGURE);
    }
}
