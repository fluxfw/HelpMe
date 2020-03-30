<?php

namespace srag\DataTableUI\HelpMe\Component\Format\Browser;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Format\Browser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @return BrowserFormat
     */
    public function default() : BrowserFormat;
}
