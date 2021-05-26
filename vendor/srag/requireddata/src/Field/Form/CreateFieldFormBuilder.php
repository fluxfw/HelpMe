<?php

namespace srag\RequiredData\HelpMe\Field\Form;

use ILIAS\UI\Component\Input\Field\Radio;
use srag\CustomInputGUIs\HelpMe\FormBuilder\AbstractFormBuilder;
use srag\RequiredData\HelpMe\Field\AbstractField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class CreateFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Form
 */
class CreateFieldFormBuilder extends AbstractFormBuilder
{

    use RequiredDataTrait;

    /**
     * @var AbstractField|null
     */
    protected $field = null;


    /**
     * @inheritDoc
     *
     * @param FieldCtrl $parent
     */
    public function __construct(FieldCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @return AbstractField
     */
    public function getField() : AbstractField
    {
        return $this->field;
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [
            FieldCtrl::CMD_CREATE_FIELD => self::requiredData()->getPlugin()->translate("add", FieldsCtrl::LANG_MODULE),
            FieldCtrl::CMD_BACK         => self::requiredData()->getPlugin()->translate("cancel", FieldsCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [
            "type" => current(array_keys(self::requiredData()->fields()->factory()->getClasses(true, $this->parent->getParent()->getParentContext(), $this->parent->getParent()->getParentId())))
        ];

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "type" => array_reduce(self::requiredData()->fields()->factory()->getClasses(true, $this->parent->getParent()->getParentContext(), $this->parent->getParent()->getParentId()),
                function (Radio $radio, string $class) : Radio {
                    $radio = $radio->withOption($class::getType(), self::requiredData()->fields()->factory()->newInstance($class::getType())->getTypeTitle());

                    return $radio;
                }, self::dic()->ui()->factory()->input()->field()->radio(self::requiredData()->getPlugin()->translate("type", FieldsCtrl::LANG_MODULE))->withRequired(true))
        ];

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::requiredData()->getPlugin()->translate("add_field", FieldsCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        $this->field = self::requiredData()->fields()->factory()->newInstance(strval($data["type"]));

        $this->field->setParentContext($this->parent->getParent()->getParentContext());
        $this->field->setParentId($this->parent->getParent()->getParentId());

        self::requiredData()->fields()->storeField($this->field);
    }
}
