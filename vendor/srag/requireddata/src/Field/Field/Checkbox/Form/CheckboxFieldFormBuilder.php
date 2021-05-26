<?php

namespace srag\RequiredData\HelpMe\Field\Field\Checkbox\Form;

use srag\RequiredData\HelpMe\Field\Field\Checkbox\CheckboxField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class CheckboxFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Checkbox\Form
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
