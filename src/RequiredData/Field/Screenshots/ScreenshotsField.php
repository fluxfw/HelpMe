<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Screenshots;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class ScreenshotsField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Screenshots
 */
class ScreenshotsField extends AbstractField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TABLE_NAME_SUFFIX = "scsh";


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
    public function getFieldDescription() : string
    {
        return "";
    }


    /**
     * @inheritDoc
     */
    public function getTypeTitle() : string
    {
        return self::plugin()->translate("screenshots", SupportGUI::LANG_MODULE);
    }
}
