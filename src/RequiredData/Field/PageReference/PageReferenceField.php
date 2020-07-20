<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\PageReference;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\DynamicValueField;

/**
 * Class PageReferenceField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\PageReference
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PageReferenceField extends DynamicValueField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const TABLE_NAME_SUFFIX = "pgrf";


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
        return self::helpMe()->support()->getRefLink();
    }


    /**
     * @inheritDoc
     */
    public function getTypeTitle() : string
    {
        return self::plugin()->translate("page_reference", SupportGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function getInitHide() : bool
    {
        return false;
    }
}
