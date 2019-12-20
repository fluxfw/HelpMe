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
    protected $key = "";
    /**
     * @var string
     */
    protected $label = "";
    /**
     * @var string
     */
    protected $value = "";


    /**
     * SupportField constructor
     *
     * @param string $key
     * @param string $label
     * @param string $value
     */
    public function __construct(string $key, string $label, string $value)
    {
        $this->key = $key;
        $this->label = $label;
        $this->value = $value;
    }


    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }


    /**
     * @return string
     */
    public function getLabel() : string
    {
        return $this->label;
    }


    /**
     * @return string
     */
    public function getValue() : string
    {
        return $this->value;
    }
}
