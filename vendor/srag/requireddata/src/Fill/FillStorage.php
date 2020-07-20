<?php

namespace srag\RequiredData\HelpMe\Fill;

use ActiveRecord;
use arConnector;
use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class FillStorage
 *
 * @package srag\RequiredData\HelpMe\Fill
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FillStorage extends ActiveRecord
{

    use DICTrait;
    use RequiredDataTrait;

    const TABLE_NAME_SUFFIX = "store";


    /**
     * @return string
     */
    public static function getTableName() : string
    {
        return self::requiredData()->getTableNamePrefix() . "_fll_" . self::TABLE_NAME_SUFFIX;
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return self::getTableName();
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::getTableName();
    }


    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     * @con_sequence     true
     */
    protected $fill_storage_id;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $fill_id;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $field_id;
    /**
     * @var mixed
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $fill_value;


    /**
     * FillStorage constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "fill_value":
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
            case "fill_value":
                return json_decode($field_value, true);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }


    /**
     * @return int
     */
    public function getFillStorageId() : int
    {
        return $this->fill_storage_id;
    }


    /**
     * @param int $fill_storage_id
     */
    public function setFillStorageId(int $fill_storage_id) : void
    {
        $this->fill_storage_id = $fill_storage_id;
    }


    /**
     * @return string
     */
    public function getFillId() : string
    {
        return $this->fill_id;
    }


    /**
     * @param string $fill_id
     */
    public function setFillId(string $fill_id) : void
    {
        $this->fill_id = $fill_id;
    }


    /**
     * @return string
     */
    public function getFieldId() : string
    {
        return $this->field_id;
    }


    /**
     * @param string $field_id
     */
    public function setFieldId(string $field_id) : void
    {
        $this->field_id = $field_id;
    }


    /**
     * @return mixed
     */
    public function getFillValue()
    {
        return $this->fill_value;
    }


    /**
     * @param mixed $fill_value
     */
    public function setFillValue($fill_value) : void
    {
        $this->fill_value = $fill_value;
    }
}
