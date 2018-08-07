<?php

namespace srag\DIC;

use ilPlugin;
use ilTemplate;

/**
 * Trait DIC
 *
 * @package srag\DIC
 *
 * @param ilPlugin $pl
 */
trait DIC {

	/**
	 * @return IDIC
	 */
	protected static function dic() {
		return DICCache::dic();
	}


	/*
	 * @param string $key
	 * @param bool   $plugin
	 *
	 * @return string
	 */
	protected function txt($key, $plugin = true) {
		if ($this instanceof ilPlugin) {
			// No overflow recursive
			return parent::txt($key);
		}

		return self::dic()->txt($key, $plugin);
	}


	/**
	 * @param string $template
	 * @param bool   $remove_unknown_variables
	 * @param bool   $remove_empty_blocks
	 * @param bool   $plugin
	 *
	 * @return ilTemplate
	 */
	protected function getTemplate($template, $remove_unknown_variables = true, $remove_empty_blocks = true, $plugin = true) {
		if ($this instanceof ilPlugin) {
			// No overflow recursive
			return parent::getTemplate($template, $remove_unknown_variables, $remove_empty_blocks);
		}

		return self::dic()->getTemplate($template, $remove_unknown_variables, $remove_empty_blocks, $plugin);
	}
}
