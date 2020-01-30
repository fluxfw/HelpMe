<?php

namespace srag\RequiredData\HelpMe\Field\Float;

use srag\RequiredData\HelpMe\Field\Integer\IntegerField;

/**
 * Class FloatField
 *
 * @package srag\RequiredData\HelpMe\Field\Float
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FloatField extends IntegerField
{

    const TABLE_NAME_SUFFIX = "flt";
    /**
     * @var int|null
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $count_decimals = null;


    /**
     * @inheritDoc
     */
    public function getFieldDescription() : string
    {
        $description = parent::getFieldDescription();

        if ($this->count_decimals !== null) {
            if (!empty($description)) {
                $description .= "\n";
            }

            $description .= "." . str_repeat("x", htmlspecialchars($this->count_decimals));
        }

        return nl2br($description, false);
    }


    /**
     * @return int|null
     */
    public function getCountDecimals()/* : ?int*/
    {
        return $this->count_decimals;
    }


    /**
     * @param int|null $count_decimals
     */
    public function setCountDecimals(/*?*/ int $count_decimals = null)/* : void*/
    {
        $this->count_decimals = $count_decimals;
    }
}
