<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\PageReference;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\DynamicValue\DynamicValueFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class PageReferenceFieldFormGUI
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\PageReference
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PageReferenceFieldFormGUI extends DynamicValueFieldFormGUI
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
