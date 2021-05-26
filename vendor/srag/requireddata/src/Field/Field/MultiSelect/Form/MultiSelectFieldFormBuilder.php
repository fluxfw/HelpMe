<?php

namespace srag\RequiredData\HelpMe\Field\Field\MultiSelect\Form;

use srag\RequiredData\HelpMe\Field\Field\MultiSelect\MultiSelectField;
use srag\RequiredData\HelpMe\Field\Field\Select\Form\SelectFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class MultiSelectFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\MultiSelect\Form
 */
class MultiSelectFieldFormBuilder extends SelectFieldFormBuilder
{

    /**
     * @var MultiSelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, MultiSelectField $field)
    {
        parent::__construct($parent, $field);
    }
}
