<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\IssueType;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class IssueTypeField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\IssueType
 */
class IssueTypeField extends AbstractField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TABLE_NAME_SUFFIX = "isty";


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
        return self::plugin()->translate("issue_type", SupportGUI::LANG_MODULE);
    }
}
