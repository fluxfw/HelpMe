<?php

namespace srag\RequiredData\HelpMe\Field\Float;

use srag\RequiredData\HelpMe\Field\Integer\IntegerFillField;

/**
 * Class FloatFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Float
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FloatFillField extends IntegerFillField
{

    /**
     * @var FloatField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FloatField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getFormFields() : array
    {
        return array_merge(
            parent::getFormFields(),
            [
                "allowDecimals" => true
            ],
            $this->field->getCountDecimals() !== null ? [
                "setDecimals" => $this->field->getCountDecimals()
            ] : []
        );
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return floatval($fill_value);
    }
}
