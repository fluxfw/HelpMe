<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\SystemInfos;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\DynamicValue\DynamicValueFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class SystemInfosFieldFormGUI
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\SystemInfos
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SystemInfosFieldFormGUI extends DynamicValueFieldFormGUI
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
