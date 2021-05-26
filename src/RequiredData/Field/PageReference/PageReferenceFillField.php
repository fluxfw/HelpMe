<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\PageReference;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\DynamicValueFillField;

/**
 * Class PageReferenceFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\PageReference
 */
class PageReferenceFillField extends DynamicValueFillField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var PageReferenceField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(PageReferenceField $field)
    {
        parent::__construct($field);
    }
}
