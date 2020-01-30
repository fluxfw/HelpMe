<?php

namespace srag\RequiredData\HelpMe\Field;

use ilCheckboxInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TextAreaInputGUI\TextAreaInputGUI;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class AbstractFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractFieldFormGUI extends PropertyFormGUI
{

    use RequiredDataTrait;
    const LANG_MODULE = FieldsCtrl::LANG_MODULE;
    /**
     * @var AbstractField
     */
    protected $field;


    /**
     * AbstractFieldFormGUI constructor
     *
     * @param FieldCtrl     $parent
     * @param AbstractField $field
     */
    public function __construct(FieldCtrl $parent, AbstractField $field)
    {
        $this->field = $field;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            default:
                return Items::getter($this->field, $key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(FieldCtrl::CMD_UPDATE_FIELD, $this->txt("save"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*:void*/
    {
        $this->fields = [
            "enabled"      => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ],
            "name"         => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => self::requiredData()->isEnableNames(),
                self::PROPERTY_NOT_ADD  => (!self::requiredData()->isEnableNames())
            ],
            "required"     => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ],
            "labels"       => [
                self::PROPERTY_CLASS    => TabsInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_SUBITEMS => MultilangualTabsInputGUI::generate([
                    "label" => [
                        self::PROPERTY_CLASS => ilTextInputGUI::class
                    ]
                ], true),
                "setTitle"              => $this->txt("label")
            ],
            "descriptions" => [
                self::PROPERTY_CLASS    => TabsInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => MultilangualTabsInputGUI::generate([
                    "description" => [
                        self::PROPERTY_CLASS => TextAreaInputGUI::class,
                        "setRows"            => 10
                    ]
                ], true, false),
                "setTitle"              => $this->txt("description")
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("edit_field"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            default:
                Items::setter($this->field, $key, $value);
                break;
        }
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (!parent::storeForm()) {
            return false;
        }

        self::requiredData()->fields()->storeField($this->field);

        return true;
    }


    /**
     * @inheritDoc
     */
    public function txt(/*string*/ $key,/*?string*/ $default = null) : string
    {
        if ($default !== null) {
            return self::requiredData()->getPlugin()->translate($key, self::LANG_MODULE, [], true, "", $default);
        } else {
            return self::requiredData()->getPlugin()->translate($key, self::LANG_MODULE);
        }
    }
}
