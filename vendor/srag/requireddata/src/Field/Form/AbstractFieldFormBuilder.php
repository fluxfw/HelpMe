<?php

namespace srag\RequiredData\HelpMe\Field\Form;

use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\FormBuilder\AbstractFormBuilder;
use srag\CustomInputGUIs\HelpMe\InputGUIWrapperUIInputComponent\InputGUIWrapperUIInputComponent;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TextAreaInputGUI\TextAreaInputGUI;
use srag\RequiredData\HelpMe\Field\AbstractField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class AbstractFieldFormBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Form
 */
abstract class AbstractFieldFormBuilder extends AbstractFormBuilder
{

    use RequiredDataTrait;

    /**
     * @var AbstractField
     */
    protected $field;


    /**
     * @inheritDoc
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
    protected function getButtons() : array
    {
        $buttons = [
            FieldCtrl::CMD_UPDATE_FIELD => self::requiredData()->getPlugin()->translate("save", FieldsCtrl::LANG_MODULE)
        ];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [];

        foreach (array_keys($this->getFields()) as $key) {
            $data[$key] = Items::getter($this->field, $key);
        }

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = [
            "enabled" => self::dic()->ui()->factory()->input()->field()->checkbox(self::requiredData()->getPlugin()->translate("enabled", FieldsCtrl::LANG_MODULE))
        ];

        if (self::requiredData()->isEnableNames()) {
            $fields += [
                "name" => self::dic()->ui()->factory()->input()->field()->text(self::requiredData()->getPlugin()->translate("name", FieldsCtrl::LANG_MODULE),
                    self::requiredData()->getPlugin()->translate("name_info", FieldsCtrl::LANG_MODULE))
                    ->withRequired(true)
            ];
        }

        $fields += [
            "required"     => self::dic()->ui()->factory()->input()->field()->checkbox(self::requiredData()->getPlugin()->translate("required", FieldsCtrl::LANG_MODULE)),
            "labels"       => (new InputGUIWrapperUIInputComponent(new TabsInputGUI(self::requiredData()->getPlugin()->translate("label", FieldsCtrl::LANG_MODULE))))->withRequired(true),
            "descriptions" => (new InputGUIWrapperUIInputComponent(new TabsInputGUI(self::requiredData()->getPlugin()->translate("description", FieldsCtrl::LANG_MODULE))))
        ];
        MultilangualTabsInputGUI::generateLegacy($fields["labels"]->getInput(), [
            new ilTextInputGUI(self::requiredData()->getPlugin()->translate("label", FieldsCtrl::LANG_MODULE), "label")
        ], true);
        $input = new TextAreaInputGUI(self::requiredData()->getPlugin()->translate("description", FieldsCtrl::LANG_MODULE), "description");
        $input->setRows(10);
        MultilangualTabsInputGUI::generateLegacy($fields["descriptions"]->getInput(), [
            $input
        ], true, false);

        if ($this->field->supportsMultiLang()) {
            $fields += [
                "multi_lang" => self::dic()->ui()->factory()->input()->field()->checkbox(self::requiredData()->getPlugin()->translate("multi_lang", FieldsCtrl::LANG_MODULE))
            ];
        }

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return self::requiredData()->getPlugin()->translate("edit_field", FieldsCtrl::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        foreach (array_keys($this->getFields()) as $key) {
            Items::setter($this->field, $key, $data[$key]);
        }

        self::requiredData()->fields()->storeField($this->field);
    }
}
