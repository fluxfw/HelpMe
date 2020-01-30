<?php

namespace srag\RequiredData\HelpMe\Field\Checkbox;

use ilCheckboxInputGUI;
use ilUtil;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class CheckboxFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Checkbox
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CheckboxFillField extends AbstractFillField
{

    /**
     * @var CheckboxField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(CheckboxField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getFormFields() : array
    {
        return [
            PropertyFormGUI::PROPERTY_CLASS => ilCheckboxInputGUI::class
        ];
    }


    /**
     * @inheritDoc
     */
    public function formatAsJson($fill_value)
    {
        return boolval($fill_value);
    }


    /**
     * @inheritDoc
     */
    public function formatAsString($fill_value) : string
    {
        if ($fill_value) {
            $img_path = ilUtil::getImagePath("icon_ok.svg");
        } else {
            $img_path = ilUtil::getImagePath("icon_not_ok.svg");
        }

        $img_path = ILIAS_HTTP_PATH . substr($img_path, 1);

        return self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($img_path, ""));
    }
}
