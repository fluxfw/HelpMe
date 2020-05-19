<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\Screenshots;

use ilHelpMePlugin;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\UI\Component\Input\Field\Input;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\HelpMe\ScreenshotsInputGUI\ScreenshotsInputGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class ScreenshotsFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\Screenshots
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ScreenshotsFillField extends AbstractFillField
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var ScreenshotsField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(ScreenshotsField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        $input = (new InputGUIWrapperUIInputComponent(new ScreenshotsInputGUI($this->field->getLabel())))->withByline($this->field->getDescription())->withRequired($this->field->isRequired());

        $input->getInput()->withPlugin(self::plugin());

        return $input;
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return (array) $fill_value;
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return nl2br(implode("\n", array_map(function (UploadResult $screenshot) : string {
            return htmlspecialchars($screenshot->getName());
        }, (array) $fill_value)), false);
    }
}
