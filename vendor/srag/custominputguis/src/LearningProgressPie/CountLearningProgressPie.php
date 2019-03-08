<?php

namespace srag\CustomInputGUIs\HelpMe\LearningProgressPie;

/**
 * Class CountLearningProgressPie
 *
 * @package srag\CustomInputGUIs\HelpMe\LearningProgressPie
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CountLearningProgressPie extends AbstractLearningProgressPie {

	/**
	 * @var int[]
	 */
	protected $count = [];


	/**
	 * @param int[] $count
	 *
	 * @return self
	 */
	public function withCount(array $count): self {
		$this->count = $count;

		return $this;
	}


	/**
	 * @inheritdoc
	 */
	protected function parseData(): array {
		if (count($this->count) > 0) {
			return $this->count;
		} else {
			return [];
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function getCount(): int {
		return array_reduce($this->count, function (int $sum, int $count): int {
			return ($sum + $count);
		}, 0);
	}
}
