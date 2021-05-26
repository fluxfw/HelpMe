<?php

namespace srag\DataTableUI\HelpMe\Component\Data\Row;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Data\Row
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
