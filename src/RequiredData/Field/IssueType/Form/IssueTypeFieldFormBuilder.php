<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\IssueTypeField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class IssueTypeFieldFormBuilder
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class IssueTypeFieldFormBuilder extends AbstractFieldFormBuilder
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var IssueTypeField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, IssueTypeField $object)
    {
        parent::__construct($parent, $object);
    }
}
