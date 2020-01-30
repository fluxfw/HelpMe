<?php

namespace srag\RequiredData\HelpMe\Field\Float;

use ilCheckboxInputGUI;
use ilNumberInputGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Integer\IntegerFieldFormGUI;

/**
 * Class FloatFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\Float
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FloatFieldFormGUI extends IntegerFieldFormGUI
{

    /**
     * @var FloatField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, FloatField $object)
    {
        parent::__construct($parent, $object);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            case "count_decimals_checkbox":
                return ($this->object->getCountDecimals() !== null);

            default:
                return parent::getValue($key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*:void*/
    {
        parent::initFields();

        $this->fields = array_merge(
            $this->fields,
            [
                "count_decimals_checkbox" => [
                    self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                    self::PROPERTY_SUBITEMS => [
                        "count_decimals" => [
                            self::PROPERTY_CLASS => ilNumberInputGUI::class
                        ]
                    ],
                    "setTitle"              => $this->txt("count_decimals")
                ]
            ]
        );
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            case "count_decimals_checkbox":
                $this->object->setCountDecimals($value ? 0 : null);
                break;

            case "count_decimals":
                if ($this->object->getCountDecimals() !== null) {
                    $this->object->setCountDecimals($value);
                }
                break;

            default:
                parent::storeValue($key, $value);
                break;
        }
    }
}
