<?php

namespace srag\Notifications4Plugin\HelpMe\Utils;

use srag\Notifications4Plugin\HelpMe\Repository as Notifications4PluginRepository;
use srag\Notifications4Plugin\HelpMe\RepositoryInterface as Notifications4PluginRepositoryInterface;

/**
 * Trait Notifications4PluginTrait
 *
 * @package srag\Notifications4Plugin\HelpMe\Utils
 */
trait Notifications4PluginTrait
{

    /**
     * @return Notifications4PluginRepositoryInterface
     */
    protected static function notifications4plugin() : Notifications4PluginRepositoryInterface
    {
        return Notifications4PluginRepository::getInstance();
    }
}
