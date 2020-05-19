<?php

namespace srag\RequiredData\HelpMe\Field\Field\Integer;

use ILIAS\UI\Component\Input\Field\Input;
use ilNumberInputGUI;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class IntegerFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Integer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class IntegerFillField extends AbstractFillField
{

    /**
     * @var IntegerField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(IntegerField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        $input = (new InputGUIWrapperUIInputComponent(new ilNumberInputGUI($this->field->getLabel())))->withByline($this->field->getDescription())->withRequired($this->field->isRequired());

        if ($this->field->getMinValue() !== null) {
            $input->getInput()->setMinValue($this->field->getMinValue());
        }

        if ($this->field->getMaxValue() !== null) {
            $input->getInput()->setMaxValue($this->field->getMaxValue());
        }

        return $input;
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return intval($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return strval($fill_value);
    }
}
