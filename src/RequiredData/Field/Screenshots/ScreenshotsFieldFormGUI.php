<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Screenshots;

use ilHelpMePlugin;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\AbstractFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class ScreenshotsFieldFormGUI
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Screenshots
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ScreenshotsFieldFormGUI extends AbstractFieldFormGUI
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
