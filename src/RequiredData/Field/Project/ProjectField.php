<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Project;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class ProjectField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectField extends AbstractField
{

    use HelpMeTrait;
    const TABLE_NAME_SUFFIX = "pr";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


    /**
     * @inheritDoc
     */
    public function getTypeTitle() : string
    {
        return self::plugin()->translate("project", SupportGUI::LANG_MODULE);
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
    public static function canBeAddedOnlyOnce() : bool
    {
        return true;
    }
}
