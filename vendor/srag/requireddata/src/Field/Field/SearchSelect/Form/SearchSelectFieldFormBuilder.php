<?php

namespace srag\RequiredData\HelpMe\Field\Field\SearchSelect\Form;

use srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\Form\MultiSearchSelectFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\Field\SearchSelect\SearchSelectField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class SearchSelectFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\SearchSelect\Form
 */
class SearchSelectFieldFormBuilder extends MultiSearchSelectFieldFormBuilder
{

    /**
     * @var SearchSelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, SearchSelectField $field)
    {
        parent::__construct($parent, $field);
    }
}
