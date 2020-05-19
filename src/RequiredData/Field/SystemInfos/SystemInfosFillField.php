<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\SystemInfos;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\DynamicValueFillField;

/**
 * Class SystemInfosFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\SystemInfos
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SystemInfosFillField extends DynamicValueFillField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var SystemInfosField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(SystemInfosField $field)
    {
        parent::__construct($field);
    }
}
