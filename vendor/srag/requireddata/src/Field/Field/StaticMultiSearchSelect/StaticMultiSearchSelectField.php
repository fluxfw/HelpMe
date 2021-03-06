<?php

namespace srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect;

use srag\RequiredData\HelpMe\Field\Field\MultiSearchSelect\MultiSearchSelectField;

/**
 * Class StaticMultiSearchSelectField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect
 */
abstract class StaticMultiSearchSelectField extends MultiSearchSelectField
{

    /**
     * @var string
     *
     * @abstract
     */
    const TABLE_NAME_SUFFIX = "";
}
