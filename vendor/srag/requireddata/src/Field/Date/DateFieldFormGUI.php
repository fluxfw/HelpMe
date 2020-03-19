<?php

namespace srag\RequiredData\HelpMe\Field\Date;

use srag\RequiredData\HelpMe\Field\AbstractFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class DateFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\Date
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DateFieldFormGUI extends AbstractFieldFormGUI
{

    /**
     * @var DateField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, DateField $field)
    {
        parent::__construct($parent, $field);
    }
}
