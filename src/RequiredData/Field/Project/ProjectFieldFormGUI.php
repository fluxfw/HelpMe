<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Project;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\AbstractFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class ProjectFieldFormGUI
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Project
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectFieldFormGUI extends AbstractFieldFormGUI
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var ProjectField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, ProjectField $object)
    {
        parent::__construct($parent, $object);
    }
}
