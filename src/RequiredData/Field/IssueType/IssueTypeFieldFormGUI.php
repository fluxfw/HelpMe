<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\IssueType;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\AbstractFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class IssueTypeFieldFormGUI
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\IssueType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class IssueTypeFieldFormGUI extends AbstractFieldFormGUI
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
