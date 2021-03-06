<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Login;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\DynamicValueFillField;

/**
 * Class LoginFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Login
 */
class LoginFillField extends DynamicValueFillField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var LoginField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(LoginField $field)
    {
        parent::__construct($field);
    }
}
