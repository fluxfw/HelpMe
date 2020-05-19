<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\SystemInfos\Form;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\RequiredData\Field\SystemInfos\SystemInfosField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\Form\DynamicValueFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class SystemInfosFieldFormBuilder
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\SystemInfos\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SystemInfosFieldFormBuilder extends DynamicValueFieldFormBuilder
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var SystemInfosField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, SystemInfosField $object)
    {
        parent::__construct($parent, $object);
    }
}
