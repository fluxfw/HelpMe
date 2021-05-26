<?php

namespace srag\RequiredData\HelpMe\Utils;

use srag\RequiredData\HelpMe\Repository as RequiredDataRepository;

/**
 * Trait RequiredDataTrait
 *
 * @package srag\RequiredData\HelpMe\Utils
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
