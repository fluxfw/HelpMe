<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\PageReference\Form;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\RequiredData\Field\PageReference\PageReferenceField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Field\DynamicValue\Form\DynamicValueFieldFormBuilder;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class PageReferenceFieldFormBuilder
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\PageReference\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PageReferenceFieldFormBuilder extends DynamicValueFieldFormBuilder
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var PageReferenceField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, PageReferenceField $object)
    {
        parent::__construct($parent, $object);
    }
}
