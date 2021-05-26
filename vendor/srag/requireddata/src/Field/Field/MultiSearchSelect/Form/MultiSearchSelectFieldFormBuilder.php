<?php

namespace srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\Form;

use srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\MultiSearchSelectField;
use srag\RequiredData\HelpMe\Field\Field\MultiSelect\Form\MultiSelectFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class MultiSearchSelectFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\Form
 */
class MultiSearchSelectFieldFormBuilder extends MultiSelectFieldFormBuilder
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
