<?php

namespace srag\Notifications4Plugin\HelpMe\Notification\Language;

use stdClass;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface {

	/**
	 * @param stdClass $data
	 *
	 * @return NotificationLanguage
	 */
	public function fromDB(stdClass $data): NotificationLanguage;


	/**
	 * @return NotificationLanguage
	 */
	public function newInstance(): NotificationLanguage;
}
