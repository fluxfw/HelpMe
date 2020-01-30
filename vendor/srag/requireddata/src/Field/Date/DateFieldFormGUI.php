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
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, DateField $object)
    {
        parent::__construct($parent, $object);
    }
}
