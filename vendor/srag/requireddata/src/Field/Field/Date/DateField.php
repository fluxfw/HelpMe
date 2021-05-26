<?php

namespace srag\RequiredData\HelpMe\Field\Field\Date;

use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class DateField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Date
 */
class DateField extends AbstractField
{

    const TABLE_NAME_SUFFIX = "dat";


    /**
     * @inheritDoc
     */
    public function getFieldDescription() : string
    {
        return "";
    }
}
