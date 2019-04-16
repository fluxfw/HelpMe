<?php

namespace srag\Plugins\HelpMe\Exception;

use ilException;

/**
 * Class HelpMeException
 *
 * @package srag\Plugins\HelpMe\Exception
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class HelpMeException extends ilException {

	/**
	 * HelpMeException constructor
	 *
	 * @param string $message
	 * @param int    $code
	 */
	public function __construct(string $message, int $code = 0) {
		parent::__construct($message, $code);
	}
}
