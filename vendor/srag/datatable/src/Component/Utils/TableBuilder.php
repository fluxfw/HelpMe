<?php

namespace srag\DataTableUI\HelpMe\Component\Utils;

use srag\DataTableUI\HelpMe\Component\Table;

/**
 * Interface TableBuilder
 *
 * @package srag\DataTableUI\HelpMe\Component\Utils
 */
interface TableBuilder
{

    /**
     * @return Table
     */
    public function getTable() : Table;


    /**
     * @return string
     */
    public function render() : string;
}
