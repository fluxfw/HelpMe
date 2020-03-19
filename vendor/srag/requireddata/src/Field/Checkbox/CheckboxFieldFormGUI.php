<?php

namespace srag\RequiredData\HelpMe\Field\Checkbox;

use srag\RequiredData\HelpMe\Field\AbstractFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class CheckboxFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\Checkbox
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CheckboxFieldFormGUI extends AbstractFieldFormGUI
{

    /**
     * @var CheckboxField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, CheckboxField $field)
    {
        parent::__construct($parent, $field);
    }
}
