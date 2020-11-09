<?php

namespace srag\DataTableUI\HelpMe\Implementation;

use srag\DataTableUI\HelpMe\Component\Column\Factory as ColumnFactoryInterface;
use srag\DataTableUI\HelpMe\Component\Data\Factory as DataFactoryInterface;
use srag\DataTableUI\HelpMe\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\HelpMe\Component\Factory as FactoryInterface;
use srag\DataTableUI\HelpMe\Component\Format\Factory as FormatFactoryInterface;
use srag\DataTableUI\HelpMe\Component\Settings\Factory as SettingsFactoryInterface;
use srag\DataTableUI\HelpMe\Component\Table as TableInterface;
use srag\DataTableUI\HelpMe\Implementation\Column\Factory as ColumnFactory;
use srag\DataTableUI\HelpMe\Implementation\Data\Factory as DataFactory;
use srag\DataTableUI\HelpMe\Implementation\Format\Factory as FormatFactory;
use srag\DataTableUI\HelpMe\Implementation\Settings\Factory as SettingsFactory;
use srag\DataTableUI\HelpMe\Implementation\Utils\DataTableUITrait;
use srag\DIC\HelpMe\DICTrait;
use srag\DIC\HelpMe\Plugin\PluginInterface;
use srag\LibraryLanguageInstaller\HelpMe\LibraryLanguageInstaller;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\HelpMe\Implementation
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Factory implements FactoryInterface
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @inheritDoc
     */
    public function column() : ColumnFactoryInterface
    {
        return ColumnFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function data() : DataFactoryInterface
    {
        return DataFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function format() : FormatFactoryInterface
    {
        return FormatFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function installLanguages(PluginInterface $plugin) : void
    {
        LibraryLanguageInstaller::getInstance()->withPlugin($plugin)->withLibraryLanguageDirectory(__DIR__
            . "/../../lang")->updateLanguages();
    }


    /**
     * @inheritDoc
     */
    public function settings() : SettingsFactoryInterface
    {
        return SettingsFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function table(string $table_id, string $action_url, string $title, array $columns, DataFetcher $data_fetcher) : TableInterface
    {
        return new Table($table_id, $action_url, $title, $columns, $data_fetcher);
    }
}
