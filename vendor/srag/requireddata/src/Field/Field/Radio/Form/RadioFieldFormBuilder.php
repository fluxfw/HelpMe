<?php

namespace srag\RequiredData\HelpMe\Field\Field\Radio\Form;

use srag\RequiredData\HelpMe\Field\Field\Radio\RadioField;
use srag\RequiredData\HelpMe\Field\Field\Select\Form\SelectFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class RadioFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Radio\Form
 */
class RadioFieldFormBuilder extends SelectFieldFormBuilder
{

    /**
     * @var RadioField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, RadioField $field)
    {
        parent::__construct($parent, $field);
    }
}
