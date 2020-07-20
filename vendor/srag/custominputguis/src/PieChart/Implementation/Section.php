<?php

namespace srag\CustomInputGUIs\HelpMe\PieChart\Implementation;

use ILIAS\Data\Color;
use ILIAS\UI\Implementation\Component\ComponentHelper;
use srag\CustomInputGUIs\HelpMe\PieChart\Component\LegendEntry as LegendEntryInterface;
use srag\CustomInputGUIs\HelpMe\PieChart\Component\PieChartItem as PieChartItemInterface;
use srag\CustomInputGUIs\HelpMe\PieChart\Component\Section as SectionInterface;
use srag\CustomInputGUIs\HelpMe\PieChart\Component\SectionValue as SectionValueInterface;

/**
 * Class Section
 *
 * https://github.com/ILIAS-eLearning/ILIAS/tree/trunk/src/UI/Implementation/Component/Chart/PieChart/Section.php
 *
 * @package srag\CustomInputGUIs\HelpMe\PieChart\Implementation
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Section implements SectionInterface
{

    use ComponentHelper;

    /**
     * @var Color
     */
    protected $color;
    /**
     * @var LegendEntryInterface
     */
    protected $legend;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var float
     */
    protected $offset;
    /**
     * @var float
     */
    protected $percentage;
    /**
     * @var float
     */
    protected $stroke_length;
    /**
     * @var Color
     */
    protected $textColor;
    /**
     * @var SectionValueInterface
     */
    protected $value;


    /**
     * Section constructor
     *
     * @param PieChartItemInterface $item
     * @param float                 $totalValue
     * @param int                   $numSections
     * @param int                   $index
     * @param float                 $offset
     */
    public function __construct(PieChartItemInterface $item, float $totalValue, int $numSections, int $index, float $offset)
    {
        $name = $item->getName();
        $value = $item->getValue();
        $color = $item->getColor();
        $textColor = $item->getTextColor();
        $this->checkStringArg("name", $name);
        $this->name = $name;
        $this->checkFloatArg("value", $value);
        $this->checkArgInstanceOf("color", $color, Color::class);
        $this->color = $color;
        $this->checkArgInstanceOf("textColor", $textColor, Color::class);
        $this->textColor = $textColor;
        $this->checkFloatArg("totalValue", $totalValue);
        $this->checkIntArg("numSections", $numSections);
        $this->checkIntArg("index", $index);
        $this->checkFloatArg("offset", $offset);
        $this->offset = $offset;

        $this->calcPercentage($totalValue, $value);
        $this->calcStrokeLength();

        $this->legend = new LegendEntry($this->name, $numSections, $index);
        $this->value = new SectionValue($value, $this->stroke_length, $this->offset, $this->percentage);
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
    public function getLegendEntry() : LegendEntryInterface
    {
        return $this->legend;
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
    public function getOffset() : float
    {
        return $this->offset;
    }


    /**
     * @inheritDoc
     */
    public function getPercentage() : float
    {
        return $this->percentage;
    }


    /**
     * @inheritDoc
     */
    public function getStrokeLength() : float
    {
        return $this->stroke_length;
    }


    /**
     * @return Color
     */
    public function getTextColor() : Color
    {
        return $this->textColor;
    }


    /**
     * @inheritDoc
     */
    public function getValue() : SectionValueInterface
    {
        return $this->value;
    }


    /**
     * @inheritDoc
     */
    public function withTextColor(Color $textColor) : SectionInterface
    {
        $clone = clone $this;
        $clone->textColor = $textColor;

        return $clone;
    }


    /**
     * @param float $totalValue
     * @param float $sectionValue
     */
    private function calcPercentage(float $totalValue, float $sectionValue)/*: void*/
    {
        $this->percentage = $sectionValue / $totalValue * 100;
    }


    /**
     *
     */
    private function calcStrokeLength()/*: void*/
    {
        $this->stroke_length = $this->percentage / 2.549;
    }
}
