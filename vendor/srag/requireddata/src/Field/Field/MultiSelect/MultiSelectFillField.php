<?php

namespace srag\RequiredData\HelpMe\Field\Field\MultiSelect;

use ILIAS\UI\Component\Input\Field\Input;
use srag\RequiredData\HelpMe\Field\Field\Select\SelectFillField;

/**
 * Class MultiSelectFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\MultiSelect
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultiSelectFillField extends SelectFillField
{

    /**
     * @var MultiSelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(MultiSelectField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        return self::dic()
            ->ui()
            ->factory()
            ->input()
            ->field()
            ->multiSelect($this->field->getLabel(), $this->field->getSelectOptions(), $this->field->getDescription())
            ->withRequired($this->field->isRequired());
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return (array) ($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return nl2br(implode("\n", array_map(function (string $value) : string {
            return strval($this->field->getSelectOptions()[$value]);
        }, (array) ($fill_value))), false);
    }
}
