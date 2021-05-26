<?php

namespace srag\ActiveRecordConfig\HelpMe\Config;

use srag\DIC\HelpMe\DICTrait;

/**
 * Class AbstractFactory
 *
 * @package srag\ActiveRecordConfig\HelpMe\Config
 */
abstract class AbstractFactory
{

    use DICTrait;

    /**
     * AbstractFactory constructor
     */
    protected function __construct()
    {

    }


    /**
     * @return Config
     */
    public function newInstance() : Config
    {
        $config = new Config();

        return $config;
    }
}
