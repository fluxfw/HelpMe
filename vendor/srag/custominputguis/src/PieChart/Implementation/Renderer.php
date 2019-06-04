<?php

namespace srag\CustomInputGUIs\HelpMe\PieChart\Implementation;

use ILIAS\UI\Component\Component;
use ILIAS\UI\Implementation\Render\AbstractComponentRenderer;
use ILIAS\UI\Implementation\Render\ilTemplateWrapper;
use ILIAS\UI\Renderer as RendererInterface;
use ilTemplate;
use srag\CustomInputGUIs\HelpMe\PieChart\Component\PieChart as PieChartInterface;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class Renderer
 *
 * https://github.com/ILIAS-eLearning/ILIAS/tree/trunk/src/UI/Implementation/Component/Chart/PieChart/Renderer.php
 *
 * @package srag\CustomInputGUIs\HelpMe\PieChart\Implementation
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Renderer extends AbstractComponentRenderer {

	use DICTrait;


	/**
	 * @inheritDoc
	 */
	protected function getComponentInterfaceName(): array {
		return [ PieChart::class ];
	}


	/**
	 * @inheritDoc
	 */
	public function render(Component $component, RendererInterface $default_renderer) {
		$this->checkComponent($component);

		return $this->renderStandard($component, $default_renderer);
	}


	/**
	 * @param PieChartInterface $component
	 * @param RendererInterface $default_renderer
	 *
	 * @return string
	 */
	protected function renderStandard(PieChartInterface $component, RendererInterface $default_renderer): string {
		$dir = __DIR__;
		$dir = "./" . substr($dir, strpos($dir, "/Customizing/") + 1) . "/..";

		self::dic()->mainTemplate()->addCss($dir . "/css/piechart.css");

		$tpl = new ilTemplateWrapper(self::dic()->mainTemplate(), new ilTemplate(__DIR__ . "/../templates/tpl.piechart.html", true, true));

		foreach ($component->getSections() as $section) {
			$tpl->setCurrentBlock("section");
			$tpl->setVariable("STROKE_LENGTH", $section->getStrokeLength());
			$tpl->setVariable("OFFSET", $section->getOffset());
			$tpl->setVariable("SECTION_COLOR", $section->getColor()->asHex());
			$tpl->parseCurrentBlock();
		}

		if ($component->isShowLegend()) {
			foreach ($component->getSections() as $section) {
				$tpl->setCurrentBlock("legend");
				$tpl->setVariable("SECTION_COLOR", $section->getColor()->asHex());
				$tpl->setVariable("LEGEND_Y_PERCENTAGE", $section->getLegendEntry()->getYPercentage());
				$tpl->setVariable("LEGEND_TEXT_Y_PERCENTAGE", $section->getLegendEntry()->getTextYPercentage());
				$tpl->setVariable("LEGEND_FONT_SIZE", $section->getLegendEntry()->getTextSize());
				$tpl->setVariable("RECT_SIZE", $section->getLegendEntry()->getSquareSize());

				if ($component->isValuesInLegend()) {
					$section_name = sprintf($section->getName() . " (%s)", $section->getValue()->getValue());
				} else {
					$section_name = $section->getName();
				}

				$tpl->setVariable("SECTION_NAME", $section_name);
			}
			$tpl->parseCurrentBlock();
		}

		foreach ($component->getSections() as $section) {
			$tpl->setCurrentBlock("section_text");
			$tpl->setVariable("VALUE_X_PERCENTAGE", $section->getValue()->getXPercentage());
			$tpl->setVariable("VALUE_Y_PERCENTAGE", $section->getValue()->getYPercentage());
			$tpl->setVariable("SECTION_VALUE", round($section->getValue()->getValue(), 2));
			$tpl->setVariable("VALUE_FONT_SIZE", $section->getValue()->getTextSize());
			$tpl->setVariable("TEXT_COLOR", $section->getTextColor()->asHex());
			$tpl->parseCurrentBlock();
		}

		$tpl->setCurrentBlock("total");
		$total_value = $component->getCustomTotalValue();
		if (is_null($total_value)) {
			$total_value = $component->getTotalValue();
		}
		$tpl->setVariable("TOTAL_VALUE", round($total_value, 2));
		$tpl->parseCurrentBlock();

		return $tpl->get();
	}
}
