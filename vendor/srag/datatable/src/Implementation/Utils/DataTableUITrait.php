<?php

namespace srag\DataTableUI\HelpMe\Implementation\Utils;

use srag\DataTableUI\HelpMe\Component\Factory as FactoryInterface;
use srag\DataTableUI\HelpMe\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Utils
 */
trait DataTableUITrait
{

    /**
     * @return FactoryInterface
     */
    protected static function dataTableUI() : FactoryInterface
    {
        return Factory::getInstance();
    }
}
