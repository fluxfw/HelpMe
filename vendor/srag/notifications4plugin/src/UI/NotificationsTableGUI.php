<?php

namespace srag\Notifications4Plugin\HelpMe\UI;

use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\TableGUI\TableGUI;
use srag\DIC\HelpMe\Plugin\PluginInterface;
use srag\Notifications4Plugin\HelpMe\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\HelpMe\Notification\Language\NotificationLanguage;
use srag\Notifications4Plugin\HelpMe\Notification\Notification;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class NotificationsTableGUI
 *
 * @package srag\Notifications4Plugin\HelpMe\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class NotificationsTableGUI extends TableGUI {

	use Notifications4PluginTrait;
	const LANG_MODULE = CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN;
	/**
	 * @var PluginInterface
	 */
	protected $plugin;
	/**
	 * @var callable
	 */
	protected $getNotifications;
	/**
	 * @var callable
	 */
	protected $getNotificationsCount;


	/**
	 * NotificationsTableGUI constructor
	 *
	 * @param PluginInterface $plugin
	 * @param CtrlInterface   $parent
	 * @param string          $parent_cmd
	 * @param callable        $getNotifications
	 * @param callable        $getNotificationsCount
	 */
	public function __construct(PluginInterface $plugin, CtrlInterface $parent, string $parent_cmd, callable $getNotifications, callable $getNotificationsCount) {
		$this->plugin = $plugin;
		$this->getNotifications = $getNotifications;
		$this->getNotificationsCount = $getNotificationsCount;

		parent::__construct($parent, $parent_cmd);
	}


	/**
	 * @inheritdoc
	 *
	 * @param Notification $row
	 */
	protected function getColumnValue(/*string*/ $column, /*Notification*/ $row, /*int*/ $format = self::DEFAULT_FORMAT): string {
		$value = Items::getter($row, $column);

		switch ($column) {
			case "languages":
				$value = implode(", ", array_map(function (NotificationLanguage $language): string {
					return $language->getLanguage();
				}, $value));
				break;

			default:
				break;
		}

		return strval($value);
	}


	/**
	 * @inheritdoc
	 */
	public function getSelectableColumns2(): array {
		$columns = [
			"title" => "title",
			"description" => "description",
			"name" => "name",
			"default_language" => "default_language",
			"languages" => "languages"
		];

		$columns = array_map(function (string $key): array {
			return [
				"id" => $key,
				"default" => true,
				"sort" => ($key !== "languages")
			];
		}, $columns);

		return $columns;
	}


	/**
	 * @inheritdoc
	 */
	protected function initColumns()/*: void*/ {
		parent::initColumns();

		$this->addColumn($this->txt("actions"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_notification"), self::dic()->ctrl()
			->getLinkTarget($this->parent_obj, CtrlInterface::CMD_ADD_NOTIFICATION)));
	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$getNotifications = $this->getNotifications;
		$getNotificationsCount = $this->getNotificationsCount;

		$this->setExternalSegmentation(true);
		$this->setExternalSorting(true);

		$this->setDefaultOrderField("title");
		$this->setDefaultOrderDirection("asc");

		// Fix stupid ilTable2GUI !!! ...
		$this->determineLimit();
		$this->determineOffsetAndOrder();

		$this->setData($getNotifications($this->getOrderField(), $this->getOrderDirection(), intval($this->getOffset()), intval($this->getLimit())));

		$this->setMaxCount($getNotificationsCount());
	}


	/**
	 * @inheritdoc
	 */
	protected function initFilterFields()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId(strtolower(CtrlInterface::NAME) . "_" . $this->plugin->getPluginObject()->getId());
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {

	}


	/**
	 * @inheritdoc
	 *
	 * @param Notification $row
	 */
	protected function fillRow(/*Notification*/ $row)/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent_obj, CtrlInterface::GET_PARAM, $row->getId());

		parent::fillRow($row);

		$this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
			self::dic()->ui()->factory()->button()->shy($this->txt("edit"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, CtrlInterface::CMD_EDIT_NOTIFICATION)),
			self::dic()->ui()->factory()->button()->shy($this->txt("duplicate"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, CtrlInterface::CMD_DUPLICATE_NOTIFICATION)),
			self::dic()->ui()->factory()->button()->shy($this->txt("delete"), self::dic()->ctrl()
				->getLinkTarget($this->parent_obj, CtrlInterface::CMD_DELETE_NOTIFICATION_CONFIRM))
		])->withLabel($this->txt("actions"))));
	}


	/**
	 * @inheritdoc
	 */
	public function txt(/*string*/ $key,/*?string*/ $default = null): string {
		if ($default !== null) {
			return $this->plugin->translate($key, self::LANG_MODULE, [], true, "", $default);
		} else {
			return $this->plugin->translate($key, self::LANG_MODULE);
		}
	}
}
