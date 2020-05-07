<?php

namespace srag\Plugins\HelpMe\Access;

use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Ilias
 *
 * @package srag\Plugins\HelpMe\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Ilias
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


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
     * Ilias constructor
     */
    private function __construct()
    {

    }


    /**
     * @return Roles
     */
    public function roles() : Roles
    {
        return Roles::getInstance();
    }


    /**
     * @return Users
     */
    public function users() : Users
    {
        return Users::getInstance();
    }
}
