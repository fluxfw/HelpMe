<?php

namespace srag\RequiredData\HelpMe\Field\Field\Text;

use ILIAS\UI\Component\Input\Field\Input;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class TextFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Text
 */
class TextFillField extends AbstractFillField
{

    /**
     * @var TextField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(TextField $field)
    {
        parent::__construct($field);
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
        return strval($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        return self::dic()->ui()->factory()->input()->field()->text($this->field->getLabel(), $this->field->getDescription())->withRequired($this->field->isRequired());
    }
}
