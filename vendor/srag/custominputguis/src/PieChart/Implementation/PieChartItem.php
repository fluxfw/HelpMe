<?php

namespace srag\CustomInputGUIs\HelpMe\PieChart\Implementation;

use ILIAS\Data\Color;
use ILIAS\UI\Implementation\Component\ComponentHelper;
use InvalidArgumentException;
use srag\CustomInputGUIs\HelpMe\PieChart\Component\PieChartItem as PieChartItemInterface;

/**
 * Class PieChartItem
 *
 * https://github.com/ILIAS-eLearning/ILIAS/tree/trunk/src/UI/Implementation/Component/Chart/PieChart/PieChartItem.php
 *
 * @package srag\CustomInputGUIs\HelpMe\PieChart\Implementation
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class PieChartItem implements PieChartItemInterface
{

    use ComponentHelper;

    /**
     * @var Color
     */
    protected $color;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Color
     */
    protected $textColor;
    /**
     * @var float
     */
    protected $value;


    /**
     * PieChartItem constructor
     *
     * @param string     $name
     * @param float      $value
     * @param Color      $color
     * @param Color|null $textColor
     */
    public function __construct(string $name, float $value, Color $color, /*?*/ Color $textColor = null)
    {
        $this->checkStringArg("name", $name);
        $this->checkFloatArg("value", $value);
        $this->checkArgInstanceOf("color", $color, Color::class);

        if (strlen($name) > self::MAX_TITLE_CHARS) {
            throw new InvalidArgumentException(self::ERR_TOO_MANY_CHARS);
        }

        $this->name = $name;
        $this->value = $value;
        $this->color = $color;

        if (!is_null($textColor)) {
            $this->checkArgInstanceOf("textColor", $textColor, Color::class);
            $this->textColor = $textColor;
        } else {
            $this->textColor = new Color(0, 0, 0);
        }
    }


    /**
     * @inheritDoc
     */
    public function getColor() : Color
    {
        return $this->color;
    }


    /**
     * @inheritDoc
     */
    public function getName() : string
    {
        return $this->name;
    }


    /**
     * @inheritDoc
     */
    public function getTextColor() : Color
    {
        return $this->textColor;
    }


    /**
     * @inheritDoc
     */
    public function getValue() : float
    {
        return $this->value;
    }
}
