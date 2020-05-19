<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Login\Form;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\RequiredData\Field\Login\LoginField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\Form\DynamicValueFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class LoginFieldFormBuilder
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Login\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LoginFieldFormBuilder extends DynamicValueFieldFormBuilder
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
