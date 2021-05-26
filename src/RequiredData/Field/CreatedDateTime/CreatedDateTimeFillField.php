<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\DynamicValueFillField;

/**
 * Class CreatedDateTimeFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime
 */
class CreatedDateTimeFillField extends DynamicValueFillField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var CreatedDateTimeField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(CreatedDateTimeField $field)
    {
        parent::__construct($field);
    }
}
