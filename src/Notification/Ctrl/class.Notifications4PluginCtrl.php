<?php

namespace srag\Plugins\HelpMe\Notification\Ctrl;

use ilHelpMePlugin;
use srag\Notifications4Plugin\HelpMe\Ctrl\AbstractCtrl;
use srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\HelpMe\Notification\Notification\Notification;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Notifications4PluginCtrl
 *
 * @package           srag\Plugins\HelpMe\Notification\Ctrl
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\HelpMe\Notification\Ctrl\Notifications4PluginCtrl: ilHelpMeConfigGUI
 */
class Notifications4PluginCtrl extends AbstractCtrl {

	use HelpMeTrait;
	const NOTIFICATION_CLASS_NAME = Notification::class;
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 * @inheritdoc
	 */
	public function executeCommand()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		self::tickets()->showUsageConfigHint();

		parent::executeCommand();
	}


	/**
	 * @inheritdoc
	 */
	public function getPlaceholderTypes(): array {
		return [
			"support" => "object " . Support::class,
			"fields" => "array " . SupportField::class
		];
	}
}
