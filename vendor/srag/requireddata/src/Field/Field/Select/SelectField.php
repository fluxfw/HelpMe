<?php

namespace srag\RequiredData\HelpMe\Field\Field\Select;

use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class SelectField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Select
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SelectField extends AbstractField
{

    const TABLE_NAME_SUFFIX = "sel";
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $options = [];


    /**
     * @inheritDoc
     */
    public function getFieldDescription() : string
    {
        return nl2br(implode("\n", array_map(function (string $option) : string {
            return htmlspecialchars($option);
        }, $this->getSelectOptions())), false);
    }


    /**
     * @return array
     */
    public function getSelectOptions(?string $lang_key = null, bool $use_default_if_not_set = true) : array
    {
        $options = [];

        foreach ($this->options as $option) {
            $options[$option["value"]] = strval(MultilangualTabsInputGUI::getValueForLang($option["label"], $lang_key, "label", $use_default_if_not_set));
        }

        return $options;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "options":
                return json_encode($field_value);

            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "options":
                return json_decode($field_value, true);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }


    /**
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }


    /**
     * @param array $options
     */
    public function setOptions(array $options) : void
    {
        $this->options = $options;
    }
}
