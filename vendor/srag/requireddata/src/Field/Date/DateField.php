<?php

namespace srag\RequiredData\HelpMe\Field\Date;

use srag\RequiredData\HelpMe\Field\AbstractField;

/**
 * Class DateField
 *
 * @package srag\RequiredData\HelpMe\Field\Date
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
