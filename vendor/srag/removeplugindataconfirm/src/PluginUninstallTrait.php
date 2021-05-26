<?php

namespace srag\RemovePluginDataConfirm\HelpMe;

/**
 * Trait PluginUninstallTrait
 *
 * @package srag\RemovePluginDataConfirm\HelpMe
 */
trait PluginUninstallTrait
{

    use BasePluginUninstallTrait;

    /**
     * @internal
     */
    protected final function afterUninstall()/*: void*/
    {

    }


    /**
     * @return bool
     *
     * @internal
     */
    protected final function beforeUninstall() : bool
    {
        return $this->pluginUninstall();
    }
}
