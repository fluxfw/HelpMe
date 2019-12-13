<?php

namespace srag\Plugins\HelpMe\Utils;

use srag\Plugins\HelpMe\Repository;

/**
 * Trait HelpMeTrait
 *
 * @package srag\Plugins\HelpMe\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
