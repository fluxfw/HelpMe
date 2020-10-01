<?php

namespace srag\RequiredData\HelpMe\Field\Field\Group;

use ILIAS\UI\Component\Input\Field\Input;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class GroupFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Group
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class GroupFillField extends AbstractFillField
{

    /**
     * @var GroupField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(GroupField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return self::requiredData()->fills()->formatAsJsons(GroupField::PARENT_CONTEXT_FIELD_GROUP, $this->field->getFieldId(), (array) $fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return nl2br(implode("\n", self::requiredData()->fills()->formatAsStrings(GroupField::PARENT_CONTEXT_FIELD_GROUP, $this->field->getFieldId(), (array) $fill_value)), false);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        return self::dic()->ui()->factory()->input()->field()->section(self::requiredData()->fills()->getFormFields(GroupField::PARENT_CONTEXT_FIELD_GROUP, $this->field->getFieldId()),
            $this->field->getLabel(), $this->field->getDescription())->withRequired($this->field->isRequired());
    }
}
