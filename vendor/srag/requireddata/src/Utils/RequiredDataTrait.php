<?php

namespace srag\RequiredData\HelpMe\Utils;

use srag\RequiredData\HelpMe\Repository as RequiredDataRepository;

/**
 * Trait RequiredDataTrait
 *
 * @package srag\RequiredData\HelpMe\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait RequiredDataTrait
{

    /**
     * @return RequiredDataRepository
     */
    protected static function requiredData() : RequiredDataRepository
    {
        return RequiredDataRepository::getInstance();
    }
}
