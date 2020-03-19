<?php

namespace srag\RequiredData\HelpMe\Field\Select;

use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\MultiLineNewInputGUI\MultiLineNewInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\RequiredData\HelpMe\Field\AbstractFieldFormGUI;
use srag\RequiredData\HelpMe\Field\FieldCtrl;

/**
 * Class SelectFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field\Select
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SelectFieldFormGUI extends AbstractFieldFormGUI
{

    /**
     * @var SelectField
     */
    protected $field;


    /**
     * @inheritDoc
     */
    public function __construct(FieldCtrl $parent, SelectField $field)
    {
        parent::__construct($parent, $field);
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/* : void*/
    {
        parent::initFields();

        $this->fields = array_merge(
            $this->fields,
            [
                "options" => [
                    self::PROPERTY_CLASS    => MultiLineNewInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    self::PROPERTY_SUBITEMS => [
                        "label" => [
                            self::PROPERTY_CLASS    => TabsInputGUI::class,
                            self::PROPERTY_REQUIRED => true,
                            self::PROPERTY_SUBITEMS => MultilangualTabsInputGUI::generate([
                                "label" => [
                                    self::PROPERTY_CLASS => ilTextInputGUI::class
                                ]
                            ], true)
                        ],
                        "value" => [
                            self::PROPERTY_CLASS    => ilTextInputGUI::class,
                            self::PROPERTY_REQUIRED => true
                        ]
                    ]
                ]
            ]
        );
    }
}
