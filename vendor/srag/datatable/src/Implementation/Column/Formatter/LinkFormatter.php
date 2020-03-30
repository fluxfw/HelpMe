<?php

namespace srag\DataTableUI\HelpMe\Implementation\Column\Formatter;

use srag\DataTableUI\HelpMe\Component\Column\Column;
use srag\DataTableUI\HelpMe\Component\Data\Row\RowData;
use srag\DataTableUI\HelpMe\Component\Format\Format;

/**
 * Class LinkFormatter
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Column\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LinkFormatter extends DefaultFormatter
{

    /**
     * @inheritDoc
     */
    public function formatRowCell(Format $format, $title, Column $column, RowData $row, string $table_id) : string
    {
        $link = $row($column->getKey() . "_link");

        if (empty($title) || empty($link)) {
            return $title;
        }

        return self::output()->getHTML(self::dic()->ui()->factory()->link()->standard($title, $link));
    }
}
