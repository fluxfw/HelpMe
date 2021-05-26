<?php

namespace srag\RequiredData\HelpMe\Field\Field\Text;

use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class TextField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Text
 */
class TextField extends AbstractField
{

    const TABLE_NAME_SUFFIX = "txt";


    /**
     * @inheritDoc
     */
    public function getFieldDescription() : string
    {
        return "";
    }


    /**
     * @inheritDoc
     */
    public function supportsMultiLang() : bool
    {
        return true;
    }
}
