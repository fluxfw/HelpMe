<?php

namespace srag\RequiredData\HelpMe\Field\Field\Date;

use ilDate;
use ilDatePresentation;
use ilDateTime;
use ilDateTimeInputGUI;
use ILIAS\UI\Component\Input\Field\Input;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class DateFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Date
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DateFillField extends AbstractFillField
{

    /**
     * @var DateField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(DateField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        return (new InputGUIWrapperUIInputComponent(new ilDateTimeInputGUI($this->field->getLabel())))->withByline($this->field->getDescription())->withRequired($this->field->isRequired());
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return intval($fill_value instanceof ilDateTime ? $fill_value->getUnixTime() : $fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return strval(ilDatePresentation::formatDate(new ilDate(intval($fill_value), IL_CAL_UNIX)));
    }
}
