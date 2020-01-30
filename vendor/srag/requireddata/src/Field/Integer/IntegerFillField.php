<?php

namespace srag\RequiredData\HelpMe\Field\Integer;

use ilNumberInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class IntegerFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Integer
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
    public function getFormFields() : array
    {
        return array_merge([
            PropertyFormGUI::PROPERTY_CLASS => ilNumberInputGUI::class
        ],
            $this->field->getMinValue() !== null ? [
                "setMinValue" => $this->field->getMinValue()
            ] : [],
            $this->field->getMaxValue() !== null ? [
                "setMaxValue" => $this->field->getMaxValue()
            ] : []

        );
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
        return htmlspecialchars($fill_value);
    }
}
