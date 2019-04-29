<?php

namespace srag\Plugins\HelpMe\Notification\Notification;

use srag\Notifications4Plugin\HelpMe\Notification\AbstractNotification;
use srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Notification
 *
 * @package srag\Plugins\HelpMe\Notification\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Notification extends AbstractNotification {

	use HelpMeTrait;
	const TABLE_NAME = "ui_uihk_srsu_not";
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
}
