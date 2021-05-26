<?php

namespace srag\DataTableUI\HelpMe\Implementation\Data\Fetcher;

use srag\DataTableUI\HelpMe\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\HelpMe\Component\Table;
use srag\DataTableUI\HelpMe\Implementation\Utils\DataTableUITrait;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class AbstractDataFetcher
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Data\Fetcher
 */
abstract class AbstractDataFetcher implements DataFetcher
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * AbstractDataFetcher constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getNoDataText(Table $component) : string
    {
        return $component->getPlugin()->translate("no_data", Table::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function isFetchDataNeedsFilterFirstSet() : bool
    {
        return false;
    }
}
