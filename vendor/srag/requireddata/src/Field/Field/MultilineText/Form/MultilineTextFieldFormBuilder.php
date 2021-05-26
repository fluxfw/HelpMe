<?php

namespace srag\RequiredData\HelpMe\Field\Field\MultilineText\Form;

use srag\RequiredData\HelpMe\Field\Field\MultilineText\MultilineTextField;
use srag\RequiredData\HelpMe\Field\Field\Text\Form\TextFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class MultilineTextFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Field\MultilineText\Form
 */
class MultilineTextFieldFormBuilder extends TextFieldFormBuilder
{

    /**
     * @var MultilineTextField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, MultilineTextField $field)
    {
        parent::__construct($parent, $field);
    }
}
