<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class SupportField
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SupportField
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var string
     */
    public $key = "";
    /**
     * @var string
     */
    public $label = "";
    /**
     * @var string
     */
    public $name = "";
    /**
     * @var string
     */
    public $value = "";


    /**
     * SupportField constructor
     *
     * @param string $key
     * @param string $name
     * @param string $label
     * @param string $value
     */
    public function __construct(string $key, string $name, string $label, string $value)
    {
        $this->key = $key;
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
    }
}
