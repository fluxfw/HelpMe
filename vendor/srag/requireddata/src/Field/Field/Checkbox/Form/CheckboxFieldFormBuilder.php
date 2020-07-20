<?php

namespace srag\RequiredData\HelpMe\Field\Field\Checkbox\Form;

use srag\RequiredData\HelpMe\Field\Field\Checkbox\CheckboxField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class CheckboxFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Checkbox\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CheckboxFieldFormBuilder extends AbstractFieldFormBuilder
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
