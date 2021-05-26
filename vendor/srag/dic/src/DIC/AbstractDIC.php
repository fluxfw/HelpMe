<?php

namespace srag\DIC\HelpMe\DIC;

use ILIAS\DI\Container;
use srag\DIC\HelpMe\Database\DatabaseDetector;
use srag\DIC\HelpMe\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\HelpMe\DIC
 */
abstract class AbstractDIC implements DICInterface
{

    /**
     * @var Container
     */
    protected $dic;


    /**
     * @inheritDoc
     */
    public function __construct(Container &$dic)
    {
        $this->dic = &$dic;
    }


    /**
     * @inheritDoc
     */
    public function database() : DatabaseInterface
    {
        return DatabaseDetector::getInstance($this->databaseCore());
    }
}
