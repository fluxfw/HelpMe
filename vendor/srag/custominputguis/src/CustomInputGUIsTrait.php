<?php

namespace srag\CustomInputGUIs\HelpMe;

/**
 * Trait CustomInputGUIsTrait
 *
 * @package srag\CustomInputGUIs\HelpMe
 */
trait CustomInputGUIsTrait
{

    /**
     * @return CustomInputGUIs
     */
    protected static final function customInputGUIs() : CustomInputGUIs
    {
        return CustomInputGUIs::getInstance();
    }
}
