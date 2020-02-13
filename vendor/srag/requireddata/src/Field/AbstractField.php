<?php

namespace srag\RequiredData\HelpMe\Field;

use ActiveRecord;
use arConnector;
use LogicException;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class AbstractField
 *
 * @package srag\RequiredData\HelpMe\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractField extends ActiveRecord
{

    use DICTrait;
    use RequiredDataTrait;
    /**
     * @var string
     *
     * @abstract
     */
    const TABLE_NAME_SUFFIX = "";


    /**
     * @return string
     */
    public static function getTableName() : string
    {
        if (empty(static::TABLE_NAME_SUFFIX)) {
            throw new LogicException("table name suffix is empty - please override TABLE_NAME_SUFFIX!");
        }

        return self::requiredData()->getTableNamePrefix() . "_fld_" . static::TABLE_NAME_SUFFIX;
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return static::getTableName();
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return static::getTableName();
    }


    /**
     * @return string
     */
    public static function getType() : string
    {
        return strtolower(end(explode("\\", static::class)));
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
    protected $field_id;
    /**
     * @var bool
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       1
     * @con_is_notnull   true
     */
    protected $enabled = true;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $parent_context;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $parent_id;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $sort = 0;
    /**
     * @var string|null
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   false
     */
    protected $name = null;
    /**
     * @var bool
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       1
     * @con_is_notnull   true
     */
    protected $required = true;
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $label = [];
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $description = [];


    /**
     * AbstractField constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, arConnector $connector = null)
    {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @return string
     */
    public function getTypeTitle() : string
    {
        return self::requiredData()->getPlugin()->translate("type_" . static::getType(), FieldsCtrl::LANG_MODULE);
    }


    /**
     * @return string
     */
    public function getFieldTitle() : string
    {
        return $this->getTypeTitle() . " " . $this->getLabel();
    }


    /**
     * @return string
     */
    public abstract function getFieldDescription() : string;


    /**
     * @return string
     */
    public function getId() : string
    {
        return self::getType() . "_" . $this->field_id;
    }


    /**
     * @return array
     */
    public function getLabels() : array
    {
        return $this->label;
    }


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getLabel(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string
    {
        return strval(MultilangualTabsInputGUI::getValueForLang($this->label, $lang_key, "label", $use_default_if_not_set));
    }


    /**
     * @param array $labels
     */
    public function setLabels(array $labels)/*:void*/
    {
        $this->label = $labels;
    }


    /**
     * @param string $label
     * @param string $lang_key
     */
    public function setLabel(string $label, string $lang_key)/*: void*/
    {
        MultilangualTabsInputGUI::setValueForLang($this->label, $label, $lang_key, "label");
    }


    /**
     * @return array
     */
    public function getDescriptions() : array
    {
        return $this->description;
    }


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getDescription(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string
    {
        return nl2br(strval(MultilangualTabsInputGUI::getValueForLang($this->description, $lang_key, "description", $use_default_if_not_set)), false);
    }


    /**
     * @param array $descriptions
     */
    public function setDescriptions(array $descriptions)/*:void*/
    {
        $this->description = $descriptions;
    }


    /**
     * @param string $description
     * @param string $lang_key
     */
    public function setDescription(string $description, string $lang_key)/*: void*/
    {
        MultilangualTabsInputGUI::setValueForLang($this->description, $description, $lang_key, "description");
    }


    /**
     * @return bool
     */
    public static function canBeAddedOnlyOnce() : bool
    {
        return false;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "enabled":
            case "required":
                return ($field_value ? 1 : 0);

            case "description":
            case "label":
                return json_encode($field_value);

            default:
                return null;
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "enabled":
            case "required":
                return boolval($field_value);

            case "description":
            case "label":
                return json_decode($field_value, true);

            default:
                return null;
        }
    }


    /**
     * @return int
     */
    public function getFieldId() : int
    {
        return $this->field_id;
    }


    /**
     * @param int $field_id
     */
    public function setFieldId(int $field_id)/*: void*/
    {
        $this->field_id = $field_id;
    }


    /**
     * @return bool
     */
    public function isEnabled() : bool
    {
        return $this->enabled;
    }


    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)/*: void*/
    {
        $this->enabled = $enabled;
    }


    /**
     * @return int
     */
    public function getParentContext() : int
    {
        return $this->parent_context;
    }


    /**
     * @param int $parent_context
     */
    public function setParentContext(int $parent_context)/* : void*/
    {
        $this->parent_context = $parent_context;
    }


    /**
     * @return int
     */
    public function getParentId() : int
    {
        return $this->parent_id;
    }


    /**
     * @param int $parent_id
     */
    public function setParentId(int $parent_id)/* : void*/
    {
        $this->parent_id = $parent_id;
    }


    /**
     * @return int
     */
    public function getSort() : int
    {
        return $this->sort;
    }


    /**
     * @param int $sort
     */
    public function setSort(int $sort)/*: void*/
    {
        $this->sort = $sort;
    }


    /**
     * @return string|null
     */
    public function getName()/* : ?string*/
    {
        return $this->name;
    }


    /**
     * @param string|null $name
     */
    public function setName(/*?*/ string $name = null)/* : void*/
    {
        $this->name = $name;
    }


    /**
     * @return bool
     */
    public function isRequired() : bool
    {
        return $this->required;
    }


    /**
     * @param bool $required
     */
    public function setRequired(bool $required)/* : void*/
    {
        $this->required = $required;
    }
}
