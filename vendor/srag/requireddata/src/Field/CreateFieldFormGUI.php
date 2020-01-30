<?php

namespace srag\RequiredData\HelpMe\Field;

use ilRadioGroupInputGUI;
use ilRadioOption;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class CreateFieldFormGUI
 *
 * @package srag\RequiredData\HelpMe\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CreateFieldFormGUI extends PropertyFormGUI
{

    use RequiredDataTrait;
    const LANG_MODULE = FieldsCtrl::LANG_MODULE;
    /**
     * @var AbstractField|null
     */
    protected $field = null;
    /**
     * @var string
     */
    protected $type;


    /**
     * CreateFieldFormGUI constructor
     *
     * @param FieldCtrl $parent
     */
    public function __construct(FieldCtrl $parent)
    {
        $this->type = current(array_keys(self::requiredData()->fields()->factory()->getClasses(true, $parent->getParent()->getParentContext(), $parent->getParent()->getParentId())));

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            default:
                return $this->{$key};
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(FieldCtrl::CMD_CREATE_FIELD, $this->txt("add"));
        $this->addCommandButton(FieldCtrl::CMD_BACK, $this->txt("cancel"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "type" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_SUBITEMS => array_map(function (string $class) : array {
                    return [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => self::requiredData()->fields()->factory()->newInstance($class::getType())->getTypeTitle()
                    ];
                }, self::requiredData()->fields()->factory()->getClasses(true, $this->parent->getParent()->getParentContext(), $this->parent->getParent()->getParentId()))
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
        $this->setTitle($this->txt("add_field"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            default:
                $this->{$key} = $value;
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

        $this->field = self::requiredData()->fields()->factory()->newInstance($this->type);

        $this->field->setParentContext($this->parent->getParent()->getParentContext());
        $this->field->setParentId($this->parent->getParent()->getParentId());

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


    /**
     * @return AbstractField
     */
    public function getField() : AbstractField
    {
        return $this->field;
    }
}
