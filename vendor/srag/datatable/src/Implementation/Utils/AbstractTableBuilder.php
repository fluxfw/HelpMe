<?php

namespace srag\DataTableUI\HelpMe\Implementation\Utils;

use srag\DataTableUI\HelpMe\Component\Table;
use srag\DataTableUI\HelpMe\Component\Utils\TableBuilder;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class AbstractTableBuilder
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractTableBuilder implements TableBuilder
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var object
     */
    protected $parent;
    /**
     * @var Table|null
     */
    protected $table = null;


    /**
     * AbstractTableBuilder constructor
     *
     * @param object $parent
     */
    public function __construct(/*object*/ $parent)
    {
        $this->parent = $parent;
    }


    /**
     * @return Table
     */
    protected abstract function buildTable() : Table;


    /**
     * @inheritDoc
     */
    public function getTable() : Table
    {
        if ($this->table === null) {
            $this->table = $this->buildTable();
        }

        return $this->table;
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        return self::output()->getHTML($this->getTable());
    }
}
