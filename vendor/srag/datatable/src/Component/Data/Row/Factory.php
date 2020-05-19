<?php

namespace srag\DataTableUI\HelpMe\Component\Data\Row;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Data\Row
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @param string $row_id
     * @param object $original_data
     *
     * @return RowData
     */
    public function getter(string $row_id, object $original_data) : RowData;


    /**
     * @param string $row_id
     * @param object $original_data
     *
     * @return RowData
     */
    public function property(string $row_id, object $original_data) : RowData;
}
