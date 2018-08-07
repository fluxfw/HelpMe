<?php

namespace srag\DIC;

use ilAccessHandler;
use ilAppEventHandler;
use ilCtrl;
use ilDBInterface;
use ILIAS\DI\BackgroundTaskServices;
use ILIAS\DI\HTTPServices;
use ILIAS\DI\LoggingServices;
use ILIAS\DI\UIServices;
use ILIAS\Filesystem\Filesystems;
use ILIAS\FileUpload\FileUpload;
use ilLanguage;
use ilLog;
use ilMailMimeSenderFactory;
use ilObjUser;
use ilRbacAdmin;
use ilRbacReview;
use ilRbacSystem;
use ilSetting;
use ilTabsGUI;
use ilTemplate;
use ilToolbarGUI;
use ilTree;

/**
 * Class LegacyDIC
 *
 * @package srag\DIC
 */
final class LegacyDIC extends ADIC {

	/**
	 * @var array
	 */
	private $globals;


	/**
	 * LegacyDIC constructor
	 *
	 * @param array $globals
	 */
	public function __construct(array &$globals) {
		parent::__construct();

		$this->globals = &$globals;
	}


	/**
	 * @return ilAccessHandler
	 */
	public function access() {
		return $this->globals["ilAccess"];
	}


	/**
	 * @return ilAppEventHandler
	 */
	public function appEventHandler() {
		return $this->globals["ilAppEventHandler"];
	}


	/**
	 * @return BackgroundTaskServices
	 *
	 * @throws DICException
	 */
	public function backgroundTasks() {
		throw new DICException("BackgroundTaskServices not exists in ILIAS 5.2 or below!");
	}


	/**
	 * @return ilCtrl
	 */
	public function ctrl() {
		return $this->globals["ilCtrl"];
	}


	/**
	 * @return ilDBInterface
	 */
	public function database() {
		return $this->globals["ilDB"];
	}


	/**
	 * @return Filesystems
	 *
	 * @throws DICException
	 */
	public function filesystem() {
		throw new DICException("Filesystems not exists in ILIAS 5.2 or below!");
	}


	/**
	 * @return HTTPServices
	 *
	 * @throws DICException
	 */
	public function http() {
		throw new DICException("HTTPServices not exists in ILIAS 5.2 or below!");
	}


	/**
	 * @return ilLanguage
	 */
	public function lng() {
		return $this->globals["lng"];
	}


	/**
	 * @return ilLog
	 */
	public function log() {
		return $this->globals["ilLog"];
	}


	/**
	 * @return LoggingServices
	 *
	 * @throws DICException
	 */
	public function logger() {
		throw new DICException("LoggingServices not exists in ILIAS 5.2 or below!");
	}


	/**
	 * @return ilMailMimeSenderFactory
	 *
	 * @throws DICException
	 */
	public function mailMimeSenderFactory() {
		throw new DICException("ilMailMimeSenderFactory not exists in ILIAS 5.2 or below!");
	}


	/**
	 * @return ilRbacAdmin
	 */
	public function rbacadmin() {
		return $this->globals["rbacadmin"];
	}


	/**
	 * @return ilRbacReview
	 */
	public function rbacreview() {
		return $this->globals["rbacreview"];
	}


	/**
	 * @return ilRbacSystem
	 */
	public function rbacsystem() {
		return $this->globals["rbacsystem"];
	}


	/**
	 * @return ilSetting
	 */
	public function settings() {
		return $this->globals["ilSetting"];
	}


	/**
	 * @return ilTabsGUI
	 */
	public function tabs() {
		return $this->globals["ilTabs"];
	}


	/**
	 * @return ilToolbarGUI
	 */
	public function toolbar() {
		return $this->globals["ilToolbar"];
	}


	/**
	 * @return ilTemplate
	 */
	public function tpl() {
		return $this->globals["tpl"];
	}


	/**
	 * @return ilTree
	 */
	public function tree() {
		return $this->globals["tree"];
	}


	/**
	 * @return UIServices
	 *
	 * @throws DICException
	 */
	public function ui() {
		throw new DICException("UIServices not exists in ILIAS 5.1 or below!");
	}


	/**
	 * @return FileUpload
	 *
	 * @throws DICException
	 */
	public function upload() {
		throw new DICException("FileUpload not exists in ILIAS 5.2 or below!");
	}


	/**
	 * @return ilObjUser
	 */
	public function user() {
		return $this->globals["ilUser"];
	}


	/**
	 * @return array
	 */
	public function &globals() {
		return $this->globals;
	}
}
