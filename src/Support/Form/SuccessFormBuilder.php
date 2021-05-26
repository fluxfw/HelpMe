<?php

namespace srag\Plugins\HelpMe\Support\Form;

use srag\Plugins\HelpMe\Support\SupportGUI;

/**
 * Class SuccessFormBuilder
 *
 * @package srag\Plugins\HelpMe\Support\Form
 */
class SuccessFormBuilder extends SupportFormBuilder
{

    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        return false;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function setButtonsToForm(string $html) : string
    {
        $first = true;

        $html = preg_replace_callback(self::REPLACE_BUTTONS_REG_EXP, function (array $matches) use (&$first) : string {
            if ($first) {
                $first = false;

                return "";
            } else {
                return '<input class="btn btn-default btn-sm" type="submit" name="cmd[]" value="' . self::plugin()->translate("close", SupportGUI::LANG_MODULE)
                    . '" id="helpme_cancel">';
            }
        }, $html);

        return $html;
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {

    }
}
