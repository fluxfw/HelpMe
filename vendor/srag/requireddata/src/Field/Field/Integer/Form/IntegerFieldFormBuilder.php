<?php

namespace srag\RequiredData\HelpMe\Field\Field\Integer\Form;

use srag\RequiredData\HelpMe\Field\Field\Integer\IntegerField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class IntegerFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Integer\Form
 */
class IntegerFieldFormBuilder extends AbstractFieldFormBuilder
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
    protected function getData() : array
    {
        $data = parent::getData();

        if ($this->field->getMinValue() !== null) {
            $data["min_value"] = [
                "value"        => true,
                "group_values" => [
                    "dependant_group" => [
                        "min_value" => $this->field->getMinValue()
                    ]
                ]
            ];
        } else {
            $data["min_value"] = [
                "value"        => false,
                "group_values" => [
                    "dependant_group" => [
                        "min_value" => 0
                    ]
                ]
            ];
        }

        if ($this->field->getMaxValue() !== null) {
            $data["max_value"] = [
                "value"        => true,
                "group_values" => [
                    "dependant_group" => [
                        "max_value" => $this->field->getMaxValue()
                    ]
                ]
            ];
        } else {
            $data["max_value"] = [
                "value"        => false,
                "group_values" => [
                    "dependant_group" => [
                        "max_value" => 0
                    ]
                ]
            ];
        }

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = parent::getFields();

        $min_value_fields = [
            "min_value" => self::dic()->ui()->factory()->input()->field()->numeric(self::requiredData()->getPlugin()->translate("min_value", FieldsCtrl::LANG_MODULE))
        ];
        $max_value_fields = [
            "max_value" => self::dic()->ui()->factory()->input()->field()->numeric(self::requiredData()->getPlugin()->translate("max_value", FieldsCtrl::LANG_MODULE))
        ];

        $fields += [
            "min_value" => self::dic()
                ->ui()
                ->factory()
                ->input()
                ->field()
                ->optionalGroup($min_value_fields, self::requiredData()->getPlugin()->translate("min_value", FieldsCtrl::LANG_MODULE)),
            "max_value" => self::dic()
                ->ui()
                ->factory()
                ->input()
                ->field()
                ->optionalGroup($max_value_fields, self::requiredData()->getPlugin()->translate("max_value", FieldsCtrl::LANG_MODULE))
        ];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        if (!empty($data["min_value"]["value"])) {
            $data["min_value"] = intval($data["min_value"]["min_value"]);
        } else {
            $data["min_value"] = null;
        }

        if (!empty($data["max_value"]["value"])) {
            $data["max_value"] = intval($data["max_value"]["max_value"]);
        } else {
            $data["max_value"] = null;
        }

        parent::storeData($data);
    }
}
