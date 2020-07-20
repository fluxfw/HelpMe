<?php

namespace srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect;

use ILIAS\UI\Component\Input\Field\Input;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\RequiredData\HelpMe\Field\Field\MultiSelect\MultiSelectFillField;

/**
 * Class MultiSearchSelectFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultiSearchSelectFillField extends MultiSelectFillField
{

    /**
     * @var MultiSearchSelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(MultiSearchSelectField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        $input = (new InputGUIWrapperUIInputComponent(new MultiSelectSearchNewInputGUI($this->field->getLabel())))->withByline($this->field->getDescription())
            ->withRequired($this->field->isRequired());

        $input->getInput()->setOptions($this->field->getSelectOptions());

        return $input;
    }
}
