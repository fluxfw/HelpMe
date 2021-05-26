<?php

namespace srag\Notifications4Plugin\HelpMe\Parser;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\HelpMe\Parser
 */
interface FactoryInterface
{

    /**
     * @return twigParser
     */
    public function twig() : twigParser;
}
