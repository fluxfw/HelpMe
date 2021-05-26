<?php

namespace srag\RequiredData\HelpMe\Field\Field\Email;

use srag\RequiredData\HelpMe\Field\Field\Text\TextField;

/**
 * Class EmailField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Email
 */
class EmailField extends TextField
{

    const TABLE_NAME_SUFFIX = "eml";


    /**
     * @inheritDoc
     */
    public function supportsMultiLang() : bool
    {
        return false;
    }
}
