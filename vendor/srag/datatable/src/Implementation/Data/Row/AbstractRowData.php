<?php

namespace srag\DataTableUI\HelpMe\Implementation\Data\Row;

use srag\DataTableUI\HelpMe\Component\Data\Row\RowData;
use srag\DataTableUI\HelpMe\Implementation\Utils\DataTableUITrait;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class AbstractRowData
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Data\Row
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractRowData implements RowData
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var string
     */
    protected $row_id = "";
    /**
     * @var object
     */
    protected $original_data;


    /**
     * AbstractRowData constructor
     *
     * @param string $row_id
     * @param object $original_data
     */
    public function __construct(string $row_id, /*object*/ $original_data)
    {
        $this->row_id = $row_id;
        $this->original_data = $original_data;
    }


    /**
     * @inheritDoc
     */
    public function getRowId() : string
    {
        return $this->row_id;
    }


    /**
     * @inheritDoc
     */
    public function withRowId(string $row_id) : RowData
    {
        $clone = clone $this;

        $clone->row_id = $row_id;

        return $clone;
    }


    /**
     * @inheritDoc
     */
    public function getOriginalData()/* : object*/
    {
        return $this->original_data;
    }


    /**
     * @inheritDoc
     */
    public function withOriginalData(/*object*/ $original_data) : RowData
    {
        $clone = clone $this;

        $clone->original_data = $original_data;

        return $clone;
    }
}
