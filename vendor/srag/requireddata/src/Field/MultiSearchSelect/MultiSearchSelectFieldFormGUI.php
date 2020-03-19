<?php

namespace srag\RequiredData\HelpMe\Field\MultiSearchSelect;

use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\MultiSelect\MultiSelectFieldFormGUI;

/**
 * Class MultiSearchSelectFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\MultiSearchSelect
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultiSearchSelectFieldFormGUI extends MultiSelectFieldFormGUI
{

    /**
     * @var MultiSearchSelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, MultiSearchSelectField $field)
    {
        parent::__construct($parent, $field);
    }
}
