<?php

namespace srag\RequiredData\HelpMe\Field\Field\Float;

use ILIAS\UI\Component\Input\Field\Input;
use srag\RequiredData\HelpMe\Field\Field\Integer\IntegerFillField;

/**
 * Class FloatFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Float
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
    public function formatAsJson($fill_value)
    {
        return floatval($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        $input = parent::getInput();

        $input->getInput()->allowDecimals(true);

        if ($this->field->getCountDecimals() !== null) {
            $input->getInput()->setDecimals($this->field->getCountDecimals());
        }

        return $input;
    }
}
