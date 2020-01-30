<?php

namespace srag\RequiredData\HelpMe\Field\StaticMultiSearchSelect;

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
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, StaticMultiSearchSelectField $object)
    {
        parent::__construct($parent, $object);
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
                }, $this->object->getOptions());

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
                "options" => [
                    self::PROPERTY_CLASS    => MultiSelectSearchNewInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_OPTIONS  => $this->deliverPossibleOptions(),
                    "setAjaxLink"           => self::dic()->ctrl()->getLinkTarget($this->parent, FieldCtrl::CMD_STATIC_MULTI_SEARCH_SELECT_GET_DATA_AUTOCOMPLETE, "", true, false)
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
            case "options":
                $this->object->setOptions(array_map(function (string $value) : array {
                    return [
                        "label" => [
                            "default" => [
                                "label" => $this->deliverPossibleOptions()[$value]
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
     * @param string|null $search
     *
     * @return array
     */
    public abstract function deliverPossibleOptions(/*?*/ string $search = null) : array;
}
