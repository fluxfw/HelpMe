<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime\Form;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime\CreatedDateTimeField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\Form\DynamicValueFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class CreatedDateTimeFieldFormBuilder
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatedDateTimeFieldFormBuilder extends DynamicValueFieldFormBuilder
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var CreatedDateTimeField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, CreatedDateTimeField $object)
    {
        parent::__construct($parent, $object);
    }
}
