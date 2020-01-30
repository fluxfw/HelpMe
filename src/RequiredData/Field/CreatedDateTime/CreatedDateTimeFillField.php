<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\DynamicValue\DynamicValueFillField;

/**
 * Class CreatedDateTimeFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
