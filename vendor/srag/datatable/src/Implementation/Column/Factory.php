<?php

namespace srag\DataTableUI\HelpMe\Implementation\Column;

use srag\DataTableUI\HelpMe\Component\Column\Column as ColumnInterface;
use srag\DataTableUI\HelpMe\Component\Column\Factory as FactoryInterface;
use srag\DataTableUI\HelpMe\Component\Column\Formatter\Factory as FormatterFactoryInterface;
use srag\DataTableUI\HelpMe\Implementation\Column\Formatter\Factory as FormatterFactory;
use srag\DataTableUI\HelpMe\Implementation\Utils\DataTableUITrait;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\HelpMe\Implementation\Column
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
    public function column(string $key, string $title) : ColumnInterface
    {
        return new Column($key, $title);
    }


    /**
     * @inheritDoc
     */
    public function formatter() : FormatterFactoryInterface
    {
        return FormatterFactory::getInstance();
    }
}
