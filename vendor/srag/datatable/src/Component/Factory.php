<?php

namespace srag\DataTableUI\HelpMe\Component;

use srag\DataTableUI\HelpMe\Component\Column\Column;
use srag\DataTableUI\HelpMe\Component\Column\Factory as ColumnFactory;
use srag\DataTableUI\HelpMe\Component\Data\Factory as DataFactory;
use srag\DataTableUI\HelpMe\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\HelpMe\Component\Format\Factory as FormatFactory;
use srag\DataTableUI\HelpMe\Component\Settings\Factory as SettingsFactory;
use srag\DIC\HelpMe\Plugin\PluginInterface;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Factory
{

    /**
     * @return ColumnFactory
     */
    public function column() : ColumnFactory;


    /**
     * @return DataFactory
     */
    public function data() : DataFactory;


    /**
     * @return FormatFactory
     */
    public function format() : FormatFactory;


    /**
     * @return SettingsFactory
     */
    public function settings() : SettingsFactory;


    /**
     * @param string      $table_id
     * @param string      $action_url
     * @param string      $title
     * @param Column[]    $columns
     * @param DataFetcher $data_fetcher
     *
     * @return Table
     */
    public function table(string $table_id, string $action_url, string $title, array $columns, DataFetcher $data_fetcher) : Table;


    /**
     * @param PluginInterface $plugin
     */
    public function installLanguages(PluginInterface $plugin)/* : void*/;
}
