<?php

namespace srag\DIC\HelpMe\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\HelpMe\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
