<?php

namespace srag\Plugins\HelpMe\Screenshot;

use HelpMeSupportGUI;
use ilFormException;
use ilFormPropertyGUI;
use ilHelpMePlugin;
use ILIAS\FileUpload\DTO\ProcessingStatus;
use ILIAS\FileUpload\DTO\UploadResult;
use ilTemplate;
use srag\DIC\DICTrait;

/**
 * Class ScreenshotsInputGUI
 *
 * @package srag\Plugins\HelpMe\Screenshot
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @since   ILIAS 5.3
 */
class ScreenshotsInputGUI extends ilFormPropertyGUI {

	use DICTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;


	/**
	 *
	 */
	public static function initJS()/*: void*/ {
		self::dic()->mainTemplate()->addJavaScript(self::plugin()->directory() . "/js/Screenshots.js", false);
		self::dic()->mainTemplate()->addOnLoadCode(self::getJSOnLoadCode());
	}


	/**
	 * @return string
	 */
	public static function getJSOnLoadCode(): string {
		$screenshot_tpl = self::plugin()->template("helpme_screenshot.html");
		$screenshot_tpl->setVariable("TXT_DELETE_SCREENSHOT", self::plugin()->translate("delete_screenshot", HelpMeSupportGUI::LANG_MODULE_SUPPORT));

		return 'il.Screenshots.PAGE_SCREENSHOT_NAME = ' . json_encode(self::plugin()
				->translate("page_screenshot", HelpMeSupportGUI::LANG_MODULE_SUPPORT)) . ';
		il.Screenshots.SCREENSHOT_TEMPLATE = ' . json_encode($screenshot_tpl->get()) . ';';
	}


	/**
	 * @var UploadResult[]
	 */
	protected $screenshots = [];


	/**
	 * ScreenshotsInputGUI constructor
	 *
	 * @param string $title
	 * @param string $post_var
	 */
	public function __construct(string $title = "", string $post_var = "") {
		parent::__construct($title, $post_var);
	}


	/**
	 * @return bool
	 */
	public function checkInput(): bool {
		$this->processScreenshots();

		if ($this->getRequired() && count($this->screenshots) === 0) {
			return false;
		}

		return true;
	}


	/**
	 * @return UploadResult[]
	 */
	public function getValue(): array {
		return $this->screenshots;
	}


	/**
	 * @param ilTemplate $tpl
	 */
	public function insert(ilTemplate $tpl) /*: void*/ {
		$html = $this->render();

		$tpl->setCurrentBlock("prop_generic");
		$tpl->setVariable("PROP_GENERIC", $html);
		$tpl->parseCurrentBlock();
	}


	/**
	 *
	 */
	protected function processScreenshots()/*: void*/ {
		// TODO: Match by post var
		if (!self::dic()->upload()->hasBeenProcessed()) {
			self::dic()->upload()->process();
		}

		if (self::dic()->upload()->hasUploads()) {
			$this->screenshots = array_values(array_filter(self::dic()->upload()->getResults(), function (UploadResult $file): bool {
				return ($file->getStatus()->getCode() === ProcessingStatus::OK);
			}));
		} else {
			$this->screenshots = [];
		}
	}


	/**
	 * @return string
	 */
	protected function render(): string {
		$screenshots_tpl = self::plugin()->template("helpme_screenshots.html");
		$screenshots_tpl->setVariable("TXT_ADD_SCREENSHOT", self::plugin()->translate("add_screenshot", HelpMeSupportGUI::LANG_MODULE_SUPPORT));
		$screenshots_tpl->setVariable("TXT_ADD_PAGE_SCREENSHOT", self::plugin()
			->translate("add_page_screenshot", HelpMeSupportGUI::LANG_MODULE_SUPPORT));
		$screenshots_tpl->setVariable("POST_VAR", $this->getPostVar());

		return $screenshots_tpl->get();
	}


	/**
	 * @param UploadResult[] $screenshots
	 *
	 * @throws ilFormException
	 */
	public function setValue(array $screenshots)/*: void*/ {
		//throw new ilFormException("ScreenshotInputGUI does not support set screenshots!");
	}


	/**
	 * @param array $values
	 *
	 * @throws ilFormException
	 */
	public function setValueByArray($values)/*: void*/ {
		//throw new ilFormException("ScreenshotInputGUI does not support set screenshots!");
	}
}
