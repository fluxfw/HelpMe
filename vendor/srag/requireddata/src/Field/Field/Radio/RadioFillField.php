<?php

namespace srag\RequiredData\HelpMe\Field\Field\Radio;

use ILIAS\UI\Component\Input\Field\Input;
use ILIAS\UI\Component\Input\Field\Radio;
use srag\RequiredData\HelpMe\Field\Field\Select\SelectFillField;

/**
 * Class RadioFillField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Radio
 */
class RadioFillField extends SelectFillField
{

    /**
     * @var RadioField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(RadioField $field)
    {
        parent::__construct($field);
    }


    /**
     * @inheritDoc
     */
    public function getInput() : Input
    {
        $options = $this->field->getSelectOptions();

        return array_reduce(array_values($options), function (Radio $radio, string $value) use ($options) : Radio {
            $radio = $radio->withOption($value, $options[$value]);

            return $radio;
        }, self::dic()->ui()->factory()->input()->field()->radio($this->field->getLabel(), $this->field->getDescription())->withRequired($this->field->isRequired()));
    }
}
