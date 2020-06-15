<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\SystemInfos;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\DynamicValueField;

/**
 * Class SystemInfosField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\SystemInfos
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SystemInfosField extends DynamicValueField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TABLE_NAME_SUFFIX = "syin";


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
        return strval(filter_input(INPUT_SERVER, "HTTP_USER_AGENT"));
    }


    /**
     * @inheritDoc
     */
    public function getTypeTitle() : string
    {
        return self::plugin()->translate("system_infos", SupportGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function getInitHide() : bool
    {
        return true;
    }
}
