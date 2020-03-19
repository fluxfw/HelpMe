<?php

namespace srag\RequiredData\HelpMe\Field\MultilineText;

use ilTextAreaInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\TextAreaInputGUI\TextAreaInputGUI;
use srag\RequiredData\HelpMe\Field\Text\TextFillField;

/**
 * Class MultilineTextFillField
 *
 * @package srag\RequiredData\HelpMe\Field\MultilineText
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultilineTextFillField extends TextFillField
{

    /**
     * @var MultilineTextField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(MultilineTextField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getFormFields() : array
    {
        return [
            PropertyFormGUI::PROPERTY_CLASS => TextAreaInputGUI::class
        ];
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return nl2br(parent::formatAsString($fill_value), false);
    }
}
