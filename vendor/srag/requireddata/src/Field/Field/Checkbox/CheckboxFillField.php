<?php

namespace srag\RequiredData\HelpMe\Field\Field\Checkbox;

use ILIAS\UI\Component\Input\Field\Input;
use ilUtil;
use srag\RequiredData\HelpMe\Fill\AbstractFillField;

/**
 * Class CheckboxFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Checkbox
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


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        return self::dic()->ui()->factory()->input()->field()->checkbox($this->field->getLabel(), $this->field->getDescription())->withRequired($this->field->isRequired());
    }
}
