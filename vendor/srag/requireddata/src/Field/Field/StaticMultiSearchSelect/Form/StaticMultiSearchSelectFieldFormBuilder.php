<?php

namespace srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect\Form;

use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\AbstractAjaxAutoCompleteCtrl;
use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\Form\MultiSearchSelectFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect\SMSSAjaxAutoCompleteCtrl;
use srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect\StaticMultiSearchSelectField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;

/**
 * Class StaticMultiSearchSelectFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect\Form
 */
abstract class StaticMultiSearchSelectFieldFormBuilder extends MultiSearchSelectFieldFormBuilder
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
     * @return AbstractAjaxAutoCompleteCtrl
     */
    public abstract function getAjaxAutoCompleteCtrl() : AbstractAjaxAutoCompleteCtrl;


    /**
     * @inheritDoc
     */
    public function storeData(array $data) : void
    {
        $data["options"] = array_map(function (string $value) : array {
            return [
                "label" => [
                    "default" => [
                        "label" => current($this->getAjaxAutoCompleteCtrl()->fillOptions([$value]))
                    ]
                ],
                "value" => $value
            ];
        }, MultiSelectSearchNewInputGUI::cleanValues((array) $data["options"]));

        parent::storeData($data);
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = parent::getData();

        $data["options"] = array_map(function (array $option) : string {
            return strval($option["value"]);
        }, $this->field->getOptions());

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = parent::getFields();

        $fields["options"] = (new InputGUIWrapperUIInputComponent(new MultiSelectSearchNewInputGUI(self::requiredData()
            ->getPlugin()
            ->translate("options", FieldsCtrl::LANG_MODULE))))->withRequired(true);
        $fields["options"]->getInput()->setAjaxAutoCompleteCtrl(new SMSSAjaxAutoCompleteCtrl($this->parent));

        return $fields;
    }
}
