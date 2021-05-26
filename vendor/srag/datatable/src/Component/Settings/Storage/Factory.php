<?php

namespace srag\DataTableUI\HelpMe\Component\Settings\Storage;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\HelpMe\Component\Settings\Storage
 */
interface Factory
{

    /**
     * @return SettingsStorage
     */
    public function default() : SettingsStorage;
}
