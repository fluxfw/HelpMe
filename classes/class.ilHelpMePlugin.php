<?php

require_once __DIR__ . "/../vendor/autoload.php";
if (file_exists(__DIR__ . "/../../../../Cron/CronHook/HelpMeCron/vendor/autoload.php")) {
    require_once __DIR__ . "/../../../../Cron/CronHook/HelpMeCron/vendor/autoload.php";
}

use ILIAS\DI\Container;
use ILIAS\GlobalScreen\Provider\PluginProviderCollection;
use srag\CustomInputGUIs\HelpMe\Loader\CustomInputGUIsLoaderDetector;
use srag\DIC\HelpMe\DevTools\DevToolsCtrl;
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

    const PLUGIN_CLASS_NAME = self::class;
    const PLUGIN_ID = "srsu";
    const PLUGIN_NAME = "HelpMe";
    /**
     * @var self|null
     */
    protected static $instance = null;
    /**
     * @var PluginProviderCollection|null
     */
    protected static $pluginProviderCollection = null;


    /**
     * ilHelpMePlugin constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->provider_collection = self::getPluginProviderCollection(); // Fix overflow
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
     * @return PluginProviderCollection
     */
    protected static function getPluginProviderCollection() : PluginProviderCollection
    {
        if (self::$pluginProviderCollection === null) {
            self::$pluginProviderCollection = new PluginProviderCollection();

            self::$pluginProviderCollection->setMetaBarProvider(self::helpMe()->metaBar());
        }

        return self::$pluginProviderCollection;
    }


    /**
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
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
    public function updateLanguages(/*?array*/ $a_lang_keys = null) : void
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
            . "/../vendor/srag/custominputguis/src/ScreenshotsInputGUI/lang")->updateLanguages();

        self::helpMe()->notifications4plugin()->installLanguages();

        self::helpMe()->requiredData()->installLanguages();

        DevToolsCtrl::installLanguages(self::plugin());
    }


    /**
     * @inheritDoc
     */
    protected function deleteData() : void
    {
        self::helpMe()->dropTables();
    }


    /**
     * @inheritDoc
     */
    protected function shouldUseOneUpdateStepOnly() : bool
    {
        return true;
    }
}
