<?php

namespace srag\Plugins\HelpMe\Notification\Notification\Language;

use srag\Notifications4Plugin\HelpMe\Notification\Language\AbstractNotificationLanguage;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class NotificationLanguage
 *
 * @package srag\Plugins\HelpMe\Notification\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class NotificationLanguage extends AbstractNotificationLanguage {

	use HelpMeTrait;
	const TABLE_NAME = "ui_uihk_srsu_not_lang";
}
