<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\PageReference;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\DynamicValue\DynamicValueFillField;

/**
 * Class PageReferenceFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\PageReference
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
