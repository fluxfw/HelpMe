<?php

namespace srag\DIC\HelpMe\DIC;

use Collator;
use ilAccessHandler;
use ilAppEventHandler;
use ilAuthSession;
use ilBenchmark;
use ilBrowser;
use ilComponentLogger;
use ilConditionService;
use ilCtrl;
use ilCtrlStructureReader;
use ilDBInterface;
use ilErrorHandling;
use ilGlobalTemplateInterface;
use ilHelpGUI;
use ILIAS;
use ILIAS\DI\BackgroundTaskServices;
use ILIAS\DI\HTTPServices;
use ILIAS\DI\LoggingServices;
use ILIAS\DI\UIServices;
use ILIAS\Filesystem\Filesystems;
use ILIAS\FileUpload\FileUpload;
use ILIAS\GlobalScreen\Services as GlobalScreenService;
use ilIniFile;
use ilLanguage;
use ilLearningHistoryService;
use ilLocatorGUI;
use ilLoggerFactory;
use ilMailMimeSenderFactory;
use ilMailMimeTransportFactory;
use ilMainMenuGUI;
use ilNavigationHistory;
use ilNewsService;
use ilObjectDataCache;
use ilObjectDefinition;
use ilObjectService;
use ilObjUser;
use ilPluginAdmin;
use ilRbacAdmin;
use ilRbacReview;
use ilRbacSystem;
use ilSetting;
use ilStyleDefinition;
use ilTabsGUI;
use ilTemplate;
use ilToolbarGUI;
use ilTree;
use Session;
use srag\DIC\HelpMe\Database\DatabaseInterface;
use srag\DIC\HelpMe\Exception\DICException;

/**
 * Interface DICInterface
 *
 * @package srag\DIC\HelpMe\DIC
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface DICInterface {

	/**
	 * @return ilAccessHandler
	 */
	public function access(): ilAccessHandler;


	/**
	 * @return ilAppEventHandler
	 */
	public function appEventHandler(): ilAppEventHandler;


	/**
	 * @return ilAuthSession
	 */
	public function authSession(): ilAuthSession;


	/**
	 * @return BackgroundTaskServices
	 *
	 * @since ILIAS 5.3
	 */
	public function backgroundTasks(): BackgroundTaskServices;


	/**
	 * @return ilBenchmark
	 */
	public function benchmark(): ilBenchmark;


	/**
	 * @return ilBrowser
	 */
	public function browser(): ilBrowser;


	/**
	 * @return ilIniFile
	 */
	public function clientIni(): ilIniFile;


	/**
	 * @return Collator
	 */
	public function collator(): Collator;


	/**
	 * @return ilConditionService
	 *
	 * @throws DICException ilConditionService not exists in ILIAS 5.3 or below!
	 *
	 * @since ILIAS 5.4
	 */
	public function conditions(): ilConditionService;


	/**
	 * @return ilCtrl
	 */
	public function ctrl(): ilCtrl;


	/**
	 * @return ilCtrlStructureReader
	 */
	public function ctrlStructureReader(): ilCtrlStructureReader;


	/**
	 * @return DatabaseInterface
	 *
	 * @throws DICException DatabaseDetector only supports ilDBPdoInterface!
	 */
	public function database(): DatabaseInterface;


	/**
	 * @return ilDBInterface
	 */
	public function databaseCore(): ilDBInterface;


	/**
	 * @return ilErrorHandling
	 */
	public function error(): ilErrorHandling;


	/**
	 * @return Filesystems
	 *
	 * @since ILIAS 5.3
	 */
	public function filesystem(): Filesystems;


	/**
	 * @return GlobalScreenService
	 *
	 * @throws DICException GlobalScreenService not exists in ILIAS 5.3 or below!
	 *
	 * @since ILIAS 5.4
	 */
	public function globalScreen(): GlobalScreenService;


	/**
	 * @return ilHelpGUI
	 */
	public function help(): ilHelpGUI;


	/**
	 * @return ilNavigationHistory
	 */
	public function history(): ilNavigationHistory;


	/**
	 * @return HTTPServices
	 *
	 * @since ILIAS 5.3
	 */
	public function http(): HTTPServices;


	/**
	 * @return ILIAS
	 */
	public function ilias(): ILIAS;


	/**
	 * @return ilIniFile
	 */
	public function iliasIni(): ilIniFile;


	/**
	 * @return ilLanguage
	 */
	public function language(): ilLanguage;


	/**
	 * @return ilLearningHistoryService
	 *
	 * @throws DICException ilLearningHistoryService not exists in ILIAS 5.3 or below!
	 *
	 * @since ILIAS 5.4
	 */
	public function learningHistory(): ilLearningHistoryService;


	/**
	 * @return ilLocatorGUI
	 */
	public function locator(): ilLocatorGUI;


	/**
	 * @return ilComponentLogger
	 */
	public function log(): ilComponentLogger;


	/**
	 * @return LoggingServices
	 *
	 * @since ILIAS 5.2
	 */
	public function logger(): LoggingServices;


	/**
	 * @return ilLoggerFactory
	 */
	public function loggerFactory(): ilLoggerFactory;


	/**
	 * @return ilMailMimeSenderFactory
	 *
	 * @since ILIAS 5.3
	 */
	public function mailMimeSenderFactory(): ilMailMimeSenderFactory;


	/**
	 * @return ilMailMimeTransportFactory
	 *
	 * @since ILIAS 5.3
	 */
	public function mailMimeTransportFactory(): ilMailMimeTransportFactory;


	/**
	 * @return ilMainMenuGUI
	 */
	public function mainMenu(): ilMainMenuGUI;


	/**
	 * @return ilTemplate|ilGlobalTemplateInterface
	 */
	public function mainTemplate();/*: ilGlobalTemplateInterface*/

	/**
	 * @return ilNewsService
	 *
	 * @throws DICException ilNewsService not exists in ILIAS 5.3 or below!
	 *
	 * @since ILIAS 5.4
	 */
	public function news(): ilNewsService;


	/**
	 * @return ilObjectDataCache
	 */
	public function objDataCache(): ilObjectDataCache;


	/**
	 * @return ilObjectDefinition
	 */
	public function objDefinition(): ilObjectDefinition;


	/**
	 * @return ilObjectService
	 *
	 * @throws DICException ilObjectService not exists in ILIAS 5.3 or below!
	 *
	 * @since ILIAS 5.4
	 */
	public function object(): ilObjectService;


	/**
	 * @return ilPluginAdmin
	 */
	public function pluginAdmin(): ilPluginAdmin;


	/**
	 * @return ilRbacAdmin
	 */
	public function rbacadmin(): ilRbacAdmin;


	/**
	 * @return ilRbacReview
	 */
	public function rbacreview(): ilRbacReview;


	/**
	 * @return ilRbacSystem
	 */
	public function rbacsystem(): ilRbacSystem;


	/**
	 * @return Session
	 */
	public function session(): Session;


	/**
	 * @return ilSetting
	 */
	public function settings(): ilSetting;


	/**
	 * @return ilStyleDefinition
	 */
	public function systemStyle(): ilStyleDefinition;


	/**
	 * @return ilTabsGUI
	 */
	public function tabs(): ilTabsGUI;


	/**
	 * @return ilToolbarGUI
	 */
	public function toolbar(): ilToolbarGUI;


	/**
	 * @return ilTree
	 */
	public function tree(): ilTree;


	/**
	 * @return UIServices
	 *
	 * @since ILIAS 5.2
	 */
	public function ui(): UIServices;


	/**
	 * @return FileUpload
	 *
	 * @since ILIAS 5.3
	 */
	public function upload(): FileUpload;


	/**
	 * @return ilObjUser
	 */
	public function user(): ilObjUser;
}
