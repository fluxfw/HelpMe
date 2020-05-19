<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\IssueType;

use ilHelpMePlugin;
use ILIAS\UI\Component\Input\Field\Input;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form\IssueTypeSelectInputGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class IssueTypeFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\IssueType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class IssueTypeFillField extends AbstractFillField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var IssueTypeField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(IssueTypeField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        return (new InputGUIWrapperUIInputComponent(new IssueTypeSelectInputGUI($this->field->getLabel())))->withByline($this->field->getDescription())
            ->withRequired($this->field->isRequired())
            ->withDisabled(true);
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return strval($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return htmlspecialchars($fill_value);
    }
}
