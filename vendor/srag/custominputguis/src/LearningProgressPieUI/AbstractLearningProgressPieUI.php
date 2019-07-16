<?php

namespace srag\CustomInputGUIs\HelpMe\LearningProgressPieUI;

use ILIAS\Data\Color;
use ilLearningProgressBaseGUI;
use ilLPStatus;
use srag\CustomInputGUIs\HelpMe\CustomInputGUIsTrait;
use srag\DIC\HelpMe\DICTrait;

/**
 * Class AbstractLearningProgressPieUI
 *
 * @package srag\CustomInputGUIs\HelpMe\LearningProgressPieUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractLearningProgressPieUI {

	use DICTrait;
	use CustomInputGUIsTrait;
	const LP_STATUS = [
		ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM,
		ilLPStatus::LP_STATUS_IN_PROGRESS_NUM,
		ilLPStatus::LP_STATUS_COMPLETED_NUM
		//ilLPStatus::LP_STATUS_FAILED_NUM
	];
	const LP_STATUS_COLOR = [
		ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM => [ 221, 221, 221 ],
		ilLPStatus::LP_STATUS_IN_PROGRESS_NUM => [ 246, 216, 66 ],
		ilLPStatus::LP_STATUS_COMPLETED_NUM => [ 189, 207, 50 ],
		ilLPStatus::LP_STATUS_FAILED => [ 176, 96, 96 ]
	];
	/**
	 * @var bool
	 */
	protected static $init = false;
	/**
	 * @var bool
	 */
	protected $show_legend = true;


	/**
	 * AbstractLearningProgressPieUI constructor
	 */
	public function __construct() {

	}


	/**
	 * @param bool show_legend
	 *
	 * @return self
	 */
	public function withShowLegend(bool $show_legend): self {
		$this->show_legend = $show_legend;

		return $this;
	}


	/**
	 * @return string
	 */
	public function render(): string {
		$data = $this->parseData();

		if (count($data) > 0) {

			$data = array_map(function (int $status) use ($data): array {
				return [
					"color" => self::LP_STATUS_COLOR[$status],
					"title" => $this->getText($status),
					"value" => $data[$status]
				];
			}, self::LP_STATUS);

			$data = array_filter($data, function (array $data): bool {
				return ($data["value"] > 0);
			});

			$data = array_values($data);

			$data = array_map(function (array $data)/*: PieChartItemInterface*/ {
				return self::customInputGUIs()
					->pieChartItem($data["title"], $data["value"], new Color($data["color"][0], $data["color"][1], $data["color"][2]));
			}, $data);

			if (count($data) > 0) {
				return self::output()->getHTML(self::customInputGUIs()->pieChart($data)->withShowLegend($this->show_legend)
					->withCustomTotalValue($this->getCount()));
			}
		}

		return "";
	}


	/**
	 * @param int $status
	 *
	 * @return string
	 */
	private function getText(int $status): string {
		self::dic()->language()->loadLanguageModule("trac");

		return ilLearningProgressBaseGUI::_getStatusText($status);
	}


	/**
	 * @return int[]
	 */
	protected abstract function parseData(): array;


	/**
	 * @return int
	 */
	protected abstract function getCount(): int;
}
