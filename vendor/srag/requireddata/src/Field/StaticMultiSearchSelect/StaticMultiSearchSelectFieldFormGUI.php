<?php

namespace srag\RequiredData\HelpMe\Field\StaticMultiSearchSelect;

use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\AbstractAjaxAutoCompleteCtrl;
use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\MultiSearchSelect\MultiSearchSelectFieldFormGUI;

/**
 * Class StaticMultiSearchSelectFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\StaticMultiSearchSelect
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class StaticMultiSearchSelectFieldFormGUI extends MultiSearchSelectFieldFormGUI
{

    /**
     * @var StaticMultiSearchSelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, StaticMultiSearchSelectField $field)
    {
        parent::__construct($parent, $field);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            case "options":
                return array_map(function (array $option) : string {
                    return strval($option["value"]);
                }, $this->field->getOptions());

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
                "options" => [
                    self::PROPERTY_CLASS      => MultiSelectSearchNewInputGUI::class,
                    self::PROPERTY_REQUIRED   => true,
                    "setAjaxAutoCompleteCtrl" => new SMSSAjaxAutoCompleteCtrl($this->parent)
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
            case "options":
                $this->field->setOptions(array_map(function (string $value) : array {
                    return [
                        "label" => [
                            "default" => [
                                "label" => current($this->getAjaxAutoCompleteCtrl()->fillOptions([$value]))
                            ]
                        ],
                        "value" => $value
                    ];
                }, $value));
                break;

            default:
                parent::storeValue($key, $value);
                break;
        }
    }


    /**
     * @return AbstractAjaxAutoCompleteCtrl
     */
    public abstract function getAjaxAutoCompleteCtrl() : AbstractAjaxAutoCompleteCtrl;
}
