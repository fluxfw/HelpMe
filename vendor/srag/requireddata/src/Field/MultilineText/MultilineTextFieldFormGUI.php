<?php

namespace srag\RequiredData\HelpMe\Field\MultilineText;

use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Text\TextFieldFormGUI;

/**
 * Class MultilineTextFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\MultilineText
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MultilineTextFieldFormGUI extends TextFieldFormGUI
{

    /**
     * @var MultilineTextField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, MultilineTextField $object)
    {
        parent::__construct($parent, $object);
    }
}
