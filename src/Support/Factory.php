<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Support\Form\SuccessFormBuilder;
use srag\Plugins\HelpMe\Support\Form\SupportFormBuilder;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @param string $key
     * @param string $name
     * @param string $label
     * @param string $value
     *
     * @return SupportField
     */
    public function newFieldInstance(string $key, string $name, string $label, string $value) : SupportField
    {
        $support_field = new SupportField($key, $name, $label, $value);

        return $support_field;
    }


    /**
     * @param SupportGUI $parent
     * @param Support    $support
     *
     * @return SupportFormBuilder
     */
    public function newFormBuilderInstance(SupportGUI $parent, Support $support) : SupportFormBuilder
    {
        $form = new SupportFormBuilder($parent, $support);

        return $form;
    }


    /**
     * @return Support
     */
    public function newInstance() : Support
    {
        $support = new Support();

        return $support;
    }


    /**
     * @param SupportGUI $parent
     * @param Support    $support
     *
     * @return SuccessFormBuilder
     */
    public function newSuccessFormBuilderInstance(SupportGUI $parent, Support $support) : SuccessFormBuilder
    {
        $form = new SuccessFormBuilder($parent, $support);

        return $form;
    }
}
