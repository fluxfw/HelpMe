<?php

namespace srag\Plugins\HelpMe\Support;

/**
 * Class SuccessFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SuccessFormGUI extends SupportFormGUI
{

    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/
        $key
    )/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton("", $this->txt("close"), "helpme_cancel");

        $this->setShowTopButtons(false);
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {

    }


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
    protected function storeValue(/*string*/
        $key,
        $value
    )/*: void*/
    {

    }
}
