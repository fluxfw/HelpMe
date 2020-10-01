<?php

namespace srag\RequiredData\HelpMe\Field\Field\DynamicValue;

use arConnector;
use srag\RequiredData\HelpMe\Field\AbstractField;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;

/**
 * Class DynamicValueField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\DynamicValue
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class DynamicValueField extends AbstractField
{

    /**
     * @var bool
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       1
     * @con_is_notnull   true
     */
    protected $hide = false;


    /**
     * @inheritDoc
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        $this->hide = $this->getInitHide();

        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @return string
     */
    public abstract function deliverDynamicValue() : string;


    /**
     * @inheritDoc
     */
    public function getFieldDescription() : string
    {
        $descriptions = [];

        if ($this->hide) {
            $descriptions[] = self::requiredData()->getPlugin()->translate("hide", FieldsCtrl::LANG_MODULE);
        }

        $descriptions[] = self::requiredData()->getPlugin()->translate("dynamic_value", FieldsCtrl::LANG_MODULE, [$this->deliverDynamicValue()]);

        return nl2br(implode("\n", array_map(function (string $description) : string {
            return htmlspecialchars($description);
        }, $descriptions)), false);
    }


    /**
     * @return bool
     */
    public function isHide() : bool
    {
        return $this->hide;
    }


    /**
     * @param bool $hide
     */
    public function setHide(bool $hide) : void
    {
        $this->hide = $hide;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "hide":
                return ($field_value ? 1 : 0);

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
            case "hide":
                return boolval($field_value);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }


    /**
     * @return bool
     */
    protected abstract function getInitHide() : bool;
}
