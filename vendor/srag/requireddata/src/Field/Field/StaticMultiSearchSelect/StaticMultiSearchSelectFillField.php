<?php

namespace srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect;

use srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\MultiSearchSelectFillField;

/**
 * Class StaticMultiSearchSelectFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class StaticMultiSearchSelectFillField extends MultiSearchSelectFillField
{

    /**
     * @var StaticMultiSearchSelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(StaticMultiSearchSelectField $field)
    {
        parent::__construct($field);
    }
}
