<?php

namespace srag\DataTableUI\HelpMe\Component\Format\Browser\Filter;

use srag\CustomInputGUIs\HelpMe\FormBuilder\FormBuilder;
use srag\DataTableUI\HelpMe\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\HelpMe\Component\Settings\Settings;
use srag\DataTableUI\HelpMe\Component\Table;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Format\Browser\Filter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param BrowserFormat $parent
     * @param Table         $component
     * @param Settings      $settings
     *
     * @return FormBuilder
     */
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings) : FormBuilder;
}
