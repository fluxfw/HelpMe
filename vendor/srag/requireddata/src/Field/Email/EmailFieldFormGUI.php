<?php

namespace srag\RequiredData\HelpMe\Field\Email;

use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Text\TextFieldFormGUI;

/**
 * Class EmailFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\Email
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class EmailFieldFormGUI extends TextFieldFormGUI
{

    /**
     * @var EmailField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, EmailField $object)
    {
        parent::__construct($parent, $object);
    }
}
