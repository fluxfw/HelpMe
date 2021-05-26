<?php

namespace srag\RequiredData\HelpMe\Field\Field\Integer;

use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class IntegerField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Integer
 */
class IntegerField extends AbstractField
{

    const TABLE_NAME_SUFFIX = "int";
    /**
     * @var int|null
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $max_value = null;
    /**
     * @var int|null
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   false
     */
    protected $min_value = null;


    /**
     * @inheritDoc
     */
    public function getFieldDescription() : string
    {
        if ($this->min_value !== null && $this->min_value !== null) {
            $description = $this->min_value . " - " . $this->max_value;
        } else {
            if ($this->min_value !== null) {
                $description = ">=" . $this->min_value;
            } else {
                if ($this->max_value !== null) {
                    $description = "<=" . $this->max_value;
                } else {
                    $description = "";
                }
            }
        }

        return htmlspecialchars($description);
    }


    /**
     * @return int|null
     */
    public function getMaxValue() : ?int
    {
        return $this->max_value;
    }


    /**
     * @param int|null $max_value
     */
    public function setMaxValue(?int $max_value = null) : void
    {
        $this->max_value = $max_value;
    }


    /**
     * @return int|null
     */
    public function getMinValue() : ?int
    {
        return $this->min_value;
    }


    /**
     * @param int|null $min_value
     */
    public function setMinValue(?int $min_value = null) : void
    {
        $this->min_value = $min_value;
    }
}
