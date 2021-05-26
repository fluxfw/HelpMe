<?php

namespace srag\RequiredData\HelpMe\Field\Field\Select;

use ILIAS\UI\Component\Input\Field\Input;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class SelectFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Select
 */
class SelectFillField extends AbstractFillField
{

    /**
     * @var SelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(SelectField $field)
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
        return strval($this->field->getSelectOptions()[strval($fill_value)]);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        return self::dic()->ui()->factory()->input()->field()->select($this->field->getLabel(), ($this->field->isRequired() && count($this->field->getSelectOptions()) === 1
                ? []
                : [
                    "&lt;" . self::requiredData()
                        ->getPlugin()
                        ->translate("please_select", FieldsCtrl::LANG_MODULE) . "&gt;"
                ]) + $this->field->getSelectOptions(), $this->field->getDescription())->withRequired($this->field->isRequired());
    }
}
