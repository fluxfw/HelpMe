<?php

namespace srag\DataTableUI\HelpMe\Component\Column\Formatter\Actions;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Column\Formatter\Actions
 */
interface Factory
{

    /**
     * @return ActionsFormatter
     */
    public function actionsDropdown() : ActionsFormatter;


    /**
     * @return ActionsFormatter
     */
    public function sort() : ActionsFormatter;
}
