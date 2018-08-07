<?php

namespace srag\DIC;

use ilPlugin;
use ilTemplate;

/**
 * Class ADIC
 *
 * @package srag\DIC
 */
abstract class ADIC implements IDIC {

	/**
	 * ADIC constructor
	 */
	protected function __construct() {

	}


	/**
	 * @return ilPlugin
	 */
	public final function pl() {
		// TODO: Implement this

		/*TODO: Find a optimal way
				static $pl_caches = [];

				$current_class = self::class;

				if (!isset($pl_caches[$current_class])) {
					$reflect = new ReflectionClass($current_class);

					$comment = $reflect->getDocComment();

					$r = "/@property[ \t]+\\\\?(il[A-Za-z0-9_\-]+Plugin)[ \t]+\\\$pl/";
					$matches = [];
					preg_match($r, $comment, $matches);
					if (is_array($matches) && count($matches) >= 2) {
						$plugin_class = $matches[1];

						if (method_exists($plugin_class, "getInstance")) {
							$plugin_instance = $plugin_class::getInstance();
							$pl_caches[$current_class] = $plugin_instance;

							return $plugin_instance;
						}
					}

					$pl_caches[$current_class] = NULL;
				}

				return $pl_caches[$current_class];*/
	}


	/**
	 * @param string $key
	 * @param bool   $plugin
	 *
	 * @return string
	 */
	public final function txt($key, $plugin = true) {
		if ($plugin) {
			return $this->pl()->txt($key);
		} else {
			return $this->lng()->txt($key);
		}
	}


	/**
	 * @param string $template
	 * @param bool   $remove_unknown_variables
	 * @param bool   $remove_empty_blocks
	 * @param bool   $plugin
	 *
	 * @return ilTemplate
	 */
	public final function getTemplate($template, $remove_unknown_variables = true, $remove_empty_blocks = true, $plugin = true) {
		if ($plugin) {
			return $this->pl()->getTemplate($template, $remove_unknown_variables, $remove_empty_blocks);
		} else {
			return new ilTemplate($template, $remove_unknown_variables, $remove_empty_blocks);
		}
	}
}
