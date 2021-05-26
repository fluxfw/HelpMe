<?php

namespace srag\RequiredData\HelpMe\Field\Field\Email\Form;

use srag\RequiredData\HelpMe\Field\Field\Email\EmailField;
use srag\RequiredData\HelpMe\Field\Field\Text\Form\TextFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class EmailFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Email\Form
 */
class EmailFieldFormBuilder extends TextFieldFormBuilder
{

    /**
     * @var EmailField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, EmailField $field)
    {
        parent::__construct($parent, $field);
    }
}
