<?php

namespace srag\RequiredData\HelpMe\Field\Field\Email;

use srag\RequiredData\HelpMe\Field\Field\Text\TextFillField;

/**
 * Class EmailFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Email
 */
class EmailFillField extends TextFillField
{

    /**
     * @var EmailField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(EmailField $field)
    {
        parent::__construct($field);
    }
}
