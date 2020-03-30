<?php

namespace srag\DataTableUI\HelpMe\Component\Settings\Sort;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Settings\Sort
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param string $sort_field
     * @param int    $sort_field_direction
     *
     * @return SortField
     */
    public function sortField(string $sort_field, int $sort_field_direction) : SortField;
}
