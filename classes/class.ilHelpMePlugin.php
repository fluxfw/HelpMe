<?php

require_once __DIR__ . "/../vendor/autoload.php";
if (file_exists(__DIR__ . "/../../../../Cron/CronHook/HelpMeCron/vendor/autoload.php")) {
    require_once __DIR__ . "/../../../../Cron/CronHook/HelpMeCron/vendor/autoload.php";
}

use srag\DIC\HelpMe\Util\LibraryLanguageInstaller;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RemovePluginDataConfirm\HelpMe\PluginUninstallTrait;

/**
 * Class ilHelpMePlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilHelpMePlugin extends ilUserInterfaceHookPlugin
{

    use PluginUninstallTrait;
    use HelpMeTrait;

    const PLUGIN_ID = "srsu";
    const PLUGIN_NAME = "HelpMe";
    const PLUGIN_CLASS_NAME = self::class;
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
     * ilHelpMePlugin constructor
     */
    public function __construct()
    {
        parent::__construct();

        if (self::version()->is60()) {
            $this->provider_collection->setMetaBarProvider(self::helpMe()->metaBar());
        }
    }


    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null)/*:void*/
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
            . "/../vendor/srag/custominputguis/src/ScreenshotsInputGUI/lang")->updateLanguages();

        self::helpMe()->notifications4plugin()->installLanguages();

        self::helpMe()->requiredData()->installLanguages();
    }


    /**
     * @inheritDoc
     */
    protected function deleteData()/*: void*/
    {
        self::helpMe()->dropTables();
    }
}
