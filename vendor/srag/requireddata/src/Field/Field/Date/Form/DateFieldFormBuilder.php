<?php

namespace srag\RequiredData\HelpMe\Field\Field\Date\Form;

use srag\RequiredData\HelpMe\Field\Field\Date\DateField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class DateFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Date\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class DateFieldFormBuilder extends AbstractFieldFormBuilder
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
