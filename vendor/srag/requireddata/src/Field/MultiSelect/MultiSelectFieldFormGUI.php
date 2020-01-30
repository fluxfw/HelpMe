<?php

namespace srag\RequiredData\HelpMe\Field\MultiSelect;

use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Select\SelectFieldFormGUI;

/**
 * Class MultiSelectFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\MultiSelect
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultiSelectFieldFormGUI extends SelectFieldFormGUI
{

    /**
     * @var MultiSelectField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, MultiSelectField $object)
    {
        parent::__construct($parent, $object);
    }
}
