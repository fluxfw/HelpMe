<?php

namespace srag\DataTableUI\HelpMe\Component\Column;

use srag\DataTableUI\HelpMe\Component\Column\Formatter\Factory as FormatterFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Column
 */
interface Factory
{

    /**
     * @param string $key
     * @param string $title
     *
     * @return Column
     */
    public function column(string $key, string $title) : Column;


    /**
     * @return FormatterFactory
     */
    public function formatter() : FormatterFactory;
}
