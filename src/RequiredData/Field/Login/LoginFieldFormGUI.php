<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Login;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\DynamicValue\DynamicValueFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class LoginFieldFormGUI
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Login
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LoginFieldFormGUI extends DynamicValueFieldFormGUI
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var LoginField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, LoginField $object)
    {
        parent::__construct($parent, $object);
    }
}
