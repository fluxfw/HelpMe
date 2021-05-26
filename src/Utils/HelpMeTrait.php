<?php

namespace srag\Plugins\HelpMe\Utils;

use srag\Plugins\HelpMe\Repository;

/**
 * Trait HelpMeTrait
 *
 * @package srag\Plugins\HelpMe\Utils
 */
trait HelpMeTrait
{

    /**
     * @return Repository
     */
    protected static function helpMe() : Repository
    {
        return Repository::getInstance();
    }
}
