<?php

namespace srag\Plugins\HelpMe\Support\Form;

use srag\Plugins\HelpMe\Support\SupportGUI;

/**
 * Class SuccessFormBuilder
 *
 * @package srag\Plugins\HelpMe\Support\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SuccessFormBuilder extends SupportFormBuilder
{

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
                return '<input class="btn btn-default btn-sm" type="submit" name="cmd[]" value="' . self::plugin()->translate("cancel", SupportGUI::LANG_MODULE)
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


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        return false;
    }
}
