<?php

namespace srag\RequiredData\HelpMe\Field\Integer;

use ilCheckboxInputGUI;
use ilNumberInputGUI;
use srag\RequiredData\HelpMe\Field\AbstractFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class IntegerFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\Integer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class IntegerFieldFormGUI extends AbstractFieldFormGUI
{

    /**
     * @var IntegerField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, IntegerField $field)
    {
        parent::__construct($parent, $field);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            case "min_value_checkbox":
                return ($this->field->getMinValue() !== null);

            case "max_value_checkbox":
                return ($this->field->getMaxValue() !== null);

            default:
                return parent::getValue($key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/* : void*/
    {
        parent::initFields();

        $this->fields = array_merge(
            $this->fields,
            [
                "min_value_checkbox" => [
                    self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                    self::PROPERTY_SUBITEMS => [
                        "min_value" => [
                            self::PROPERTY_CLASS => ilNumberInputGUI::class
                        ]
                    ],
                    "setTitle"              => $this->txt("min_value")
                ],
                "max_value_checkbox" => [
                    self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                    self::PROPERTY_SUBITEMS => [
                        "max_value" => [
                            self::PROPERTY_CLASS => ilNumberInputGUI::class
                        ]
                    ],
                    "setTitle"              => $this->txt("max_value")
                ]
            ]
        );
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/* : void*/
    {
        switch ($key) {
            case "min_value_checkbox":
                $this->field->setMinValue($value ? 0 : null);
                break;

            case "min_value":
                if ($this->field->getMinValue() !== null) {
                    $this->field->setMinValue($value);
                }
                break;

            case "max_value_checkbox":
                $this->field->setMaxValue($value ? 0 : null);
                break;

            case "max_value":
                if ($this->field->getMaxValue() !== null) {
                    $this->field->setMaxValue($value);
                }
                break;

            default:
                parent::storeValue($key, $value);
                break;
        }
    }
}
