<?php

namespace srag\DataTableUI\HelpMe\Implementation\Utils;

use srag\DataTableUI\HelpMe\Component\Factory as FactoryInterface;
use srag\DataTableUI\HelpMe\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
