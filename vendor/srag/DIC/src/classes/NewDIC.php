<?php

namespace srag\DIC;

use ilAccessHandler;
use ilAppEventHandler;
use ilCtrl;
use ilDBInterface;
use ILIAS\DI\BackgroundTaskServices;
use ILIAS\DI\Container;
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
 * Class NewDIC
 *
 * @package srag\DIC
 */
final class NewDIC extends ADIC {

	/**
	 * @var Container
	 */
	private $dic;


	/**
	 * NewDIC constructor
	 *
	 * @param Container $dic
	 */
	public function __construct(Container $dic) {
		parent::__construct();

		$this->dic = $dic;
	}


	/**
	 * @return ilAccessHandler
	 */
	public function access() {
		return $this->dic->access();
	}


	/**
	 * @return ilAppEventHandler
	 */
	public function appEventHandler() {
		return $this->dic->event();
	}


	/**
	 * @return BackgroundTaskServices
	 *
	 * @throws DICException
	 */
	public function backgroundTasks() {
		if ($this->is53()) {
			return $this->dic->backgroundTasks();
		} else {
			throw new DICException("BackgroundTaskServices not exists in ILIAS 5.2 or below!");
		}
	}


	/**
	 * @return ilCtrl
	 */
	public function ctrl() {
		return $this->dic->ctrl();
	}


	/**
	 * @return ilDBInterface
	 */
	public function database() {
		return $this->dic->database();
	}


	/**
	 * @return Filesystems
	 *
	 * @throws DICException
	 */
	public function filesystem() {
		if ($this->is53()) {
			return $this->dic->filesystem();
		} else {
			throw new DICException("Filesystems not exists in ILIAS 5.2 or below!");
		}
	}


	/**
	 * @return HTTPServices
	 *
	 * @throws DICException
	 */
	public function http() {
		if ($this->is53()) {
			return $this->dic->http();
		} else {
			throw new DICException("HTTPServices not exists in ILIAS 5.2 or below!");
		}
	}


	/**
	 * @return ilLanguage
	 */
	public function lng() {
		return $this->dic->language();
	}


	/**
	 * @return ilLog
	 */
	public function log() {
		return $this->dic["ilLog"];
	}


	/**
	 * @return LoggingServices
	 *
	 * @throws DICException
	 */
	public function logger() {
		return $this->dic->logger();
	}


	/**
	 * @return ilMailMimeSenderFactory
	 *
	 * @throws DICException
	 */
	public function mailMimeSenderFactory() {
		if ($this->is53()) {
			return $this->dic["mail.mime.sender.factory"];
		} else {
			throw new DICException("ilMailMimeSenderFactory not exists in ILIAS 5.2 or below!");
		}
	}


	/**
	 * @return ilRbacAdmin
	 */
	public function rbacadmin() {
		return $this->dic->rbac()->admin();
	}


	/**
	 * @return ilRbacReview
	 */
	public function rbacreview() {
		return $this->dic->rbac()->review();
	}


	/**
	 * @return ilRbacSystem
	 */
	public function rbacsystem() {
		return $this->dic->rbac()->system();
	}


	/**
	 * @return ilSetting
	 */
	public function settings() {
		return $this->dic->settings();
	}


	/**
	 * @return ilTabsGUI
	 */
	public function tabs() {
		return $this->dic->tabs();
	}


	/**
	 * @return ilToolbarGUI
	 */
	public function toolbar() {
		return $this->dic->toolbar();
	}


	/**
	 * @return ilTemplate
	 */
	public function tpl() {
		return $this->dic->ui()->mainTemplate();
	}


	/**
	 * @return ilTree
	 */
	public function tree() {
		return $this->dic->repositoryTree();
	}


	/**
	 * @return UIServices
	 *
	 * @throws DICException
	 */
	public function ui() {
		return $this->dic->ui();
	}


	/**
	 * @return FileUpload
	 *
	 * @throws DICException
	 */
	public function upload() {
		if ($this->is53()) {
			return $this->dic->upload();
		} else {
			throw new DICException("FileUpload not exists in ILIAS 5.2 or below!");
		}
	}


	/**
	 * @return ilObjUser
	 */
	public function user() {
		return $this->dic->user();
	}


	/**
	 * @return Container
	 */
	public function dic() {
		return $this->dic;
	}


	/**
	 * @return bool
	 */
	private function is53() {
		return (ILIAS_VERSION_NUMERIC >= "5.3");
	}
}
