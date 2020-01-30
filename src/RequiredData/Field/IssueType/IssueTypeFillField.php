<?php

namespace srag\Plugins\HelpMe\RequiredData\Field\IssueType;

use ilHelpMePlugin;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class IssueTypeFillField
 *
 * @package srag\Plugins\HelpMe\RequiredData\Field\IssueType
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class IssueTypeFillField extends AbstractFillField
{

    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var IssueTypeField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(IssueTypeField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getFormFields() : array
    {
        return [
            PropertyFormGUI::PROPERTY_CLASS    => IssueTypeSelectInputGUI::class,
            PropertyFormGUI::PROPERTY_OPTIONS  => [],
            PropertyFormGUI::PROPERTY_DISABLED => true
        ];
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return strval($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        return htmlspecialchars($fill_value);
    }
}
