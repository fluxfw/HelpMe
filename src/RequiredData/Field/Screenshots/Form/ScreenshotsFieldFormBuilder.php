<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Screenshots\Form;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\RequiredData\Field\Screenshots\ScreenshotsField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\Form\AbstractFieldFormBuilder;

/**
 * Class ScreenshotsFieldFormBuilder
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Screenshots\Form
 */
class ScreenshotsFieldFormBuilder extends AbstractFieldFormBuilder
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var ScreenshotsField
     */
    protected $object;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, ScreenshotsField $object)
    {
        parent::__construct($parent, $object);
    }
}
