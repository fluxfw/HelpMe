<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Project\Form;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\RequiredData\Field\Project\ProjectField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class ProjectFieldFormBuilder
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Project\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ProjectFieldFormBuilder extends AbstractFieldFormBuilder
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
