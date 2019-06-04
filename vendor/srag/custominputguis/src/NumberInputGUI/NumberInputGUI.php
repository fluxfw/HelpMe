<?php

namespace srag\CustomInputGUIs\HelpMe\NumberInputGUI;

use ilNumberInputGUI;
use ilTableFilterItem;
use ilToolbarItem;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class NumberInputGUI
 *
 * @package srag\CustomInputGUIs\HelpMe\NumberInputGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class NumberInputGUI extends ilNumberInputGUI implements ilTableFilterItem, ilToolbarItem {

	use DICTrait;


	/**
	 * @inheritdoc
	 */
	public function getTableFilterHTML()/*: string*/ {
		return $this->render();
	}


	/**
	 * @inheritdoc
	 */
	public function getToolbarHTML()/*: string*/ {
		return $this->render();
	}
}
