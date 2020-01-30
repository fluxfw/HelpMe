<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\DynamicValue\DynamicValueFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class CreatedDateTimeFieldFormGUI
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreatedDateTimeFieldFormGUI extends DynamicValueFieldFormGUI
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
