<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Login;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\DynamicValueField;

/**
 * Class LoginField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Login
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LoginField extends DynamicValueField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TABLE_NAME_SUFFIX = "lgn";


    /**
     * @inheritDoc
     */
    public static function canBeAddedOnlyOnce() : bool
    {
        return true;
    }


    /**
     * @inheritDoc
     */
    public function deliverDynamicValue() : string
    {
        return self::dic()->user()->getLogin();
    }


    /**
     * @inheritDoc
     */
    public function getTypeTitle() : string
    {
        return self::plugin()->translate("login", SupportGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function getInitHide() : bool
    {
        return true;
    }
}
