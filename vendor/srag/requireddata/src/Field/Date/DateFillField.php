<?php

namespace srag\RequiredData\HelpMe\Field\Date;

use ilDate;
use ilDatePresentation;
use ilDateTime;
use ilDateTimeInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class DateFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Date
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DateFillField extends AbstractFillField
{

    /**
     * @var DateField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(DateField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getFormFields() : array
    {
        return [
            PropertyFormGUI::PROPERTY_CLASS => ilDateTimeInputGUI::class
        ];
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return intval($fill_value instanceof ilDateTime ? $fill_value->getUnixTime() : $fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return htmlspecialchars(ilDatePresentation::formatDate(new ilDate(intval($fill_value), IL_CAL_UNIX)));
    }
}
