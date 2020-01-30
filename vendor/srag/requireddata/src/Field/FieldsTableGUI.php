<?php

namespace srag\RequiredData\HelpMe\Field;

use ilUtil;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\TableGUI\TableGUI;
use srag\CustomInputGUIs\HelpMe\Waiter\Waiter;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class FieldsTableGUI
 *
 * @package srag\RequiredData\HelpMe\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FieldsTableGUI extends TableGUI
{

    use RequiredDataTrait;
    const LANG_MODULE = FieldsCtrl::LANG_MODULE;


    /**
     * FieldsTableGUI constructor
     *
     * @param FieldsCtrl $parent
     * @param string     $parent_cmd
     */
    public function __construct(FieldsCtrl $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritDoc
     *
     * @param AbstractField $field
     */
    protected function getColumnValue(/*string*/ $column, /*AbstractField*/ $field, /*int*/ $format = self::DEFAULT_FORMAT) : string
    {
        switch ($column) {
            case "enabled":
            case "required":
                if (Items::getter($field, $column)) {
                    $column = ilUtil::getImagePath("icon_ok.svg");
                } else {
                    $column = ilUtil::getImagePath("icon_not_ok.svg");
                }
                $column = self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($column, ""));
                break;

            case "field_description":
                $column = $field->getFieldDescription();
                break;

            default:
                $column = htmlspecialchars(Items::getter($field, $column));
                break;
        }

        return strval($column);
    }


    /**
     * @inheritDoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "enabled" => [
                "id"      => "enabled",
                "default" => true,
                "sort"    => false
            ]
        ];

        if (self::requiredData()->isEnableNames()) {
            $columns["name"] = [
                "id"      => "name",
                "default" => true,
                "sort"    => false
            ];
        }

        $columns = array_merge($columns, [
            "enabled"           => [
                "id"      => "enabled",
                "default" => true,
                "sort"    => false
            ],
            "type_title"        => [
                "id"      => "type_title",
                "default" => true,
                "sort"    => false,
                "txt"     => $this->txt("type")
            ],
            "required"          => [
                "id"      => "required",
                "default" => true,
                "sort"    => false
            ],
            "label"             => [
                "id"      => "label",
                "default" => true,
                "sort"    => false
            ],
            "description"       => [
                "id"      => "description",
                "default" => true,
                "sort"    => false
            ],
            "field_description" => [
                "id"      => "field_description",
                "default" => true,
                "sort"    => false
            ]
        ]);

        return $columns;
    }


    /**
     * @inheritDoc
     */
    protected function initColumns()/*: void*/
    {
        $this->addColumn("");

        $this->addColumn("");

        parent::initColumns();

        $this->addColumn($this->txt("actions"));
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard($this->txt("add_field"), self::dic()->ctrl()
            ->getLinkTargetByClass(FieldCtrl::class, FieldCtrl::CMD_ADD_FIELD)));

        $this->setSelectAllCheckbox(FieldCtrl::GET_PARAM_FIELD_ID);
        $this->addMultiCommand(FieldsCtrl::CMD_ENABLE_FIELDS, $this->txt("enable_fields"));
        $this->addMultiCommand(FieldsCtrl::CMD_DISABLE_FIELD, $this->txt("disable_fields"));
        $this->addMultiCommand(FieldsCtrl::CMD_REMOVE_FIELDS_CONFIRM, $this->txt("remove_fields"));
    }


    /**
     * @inheritDoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setData(self::requiredData()->fields()->getFields($this->parent_obj->getParentContext(), $this->parent_obj->getParentId(), null, false));
    }


    /**
     * @inheritDoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {
        $this->setId("fields_" . self::requiredData()->getPlugin()->getPluginObject()->getId());
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("fields"));
    }


    /**
     * @param AbstractField $field
     */
    protected function fillRow(/*AbstractField*/ $field)/*: void*/
    {
        self::dic()->ctrl()->setParameterByClass(FieldCtrl::class, FieldCtrl::GET_PARAM_FIELD_TYPE, $field->getType());
        self::dic()->ctrl()->setParameterByClass(FieldCtrl::class, FieldCtrl::GET_PARAM_FIELD_ID, $field->getFieldId());

        $this->tpl->setCurrentBlock("checkbox");
        $this->tpl->setVariableEscaped("CHECKBOX_POST_VAR", FieldCtrl::GET_PARAM_FIELD_ID);
        $this->tpl->setVariableEscaped("ID", $field->getId());
        $this->tpl->parseCurrentBlock();
        $this->tpl->setCurrentBlock("column");
        $this->tpl->setVariable("COLUMN", self::output()->getHTML([
            self::dic()->ui()->factory()->glyph()->sortAscending()->withAdditionalOnLoadCode(function (string $id) : string {
                Waiter::init(Waiter::TYPE_WAITER);

                return '
            $("#' . $id . '").click(function () {
                il.waiter.show();
                var row = $(this).parent().parent();
                $.ajax({
                    url: ' . json_encode(self::dic()
                        ->ctrl()
                        ->getLinkTargetByClass(FieldCtrl::class, FieldCtrl::CMD_MOVE_FIELD_UP, "", true)) . ',
                    type: "GET"
                 }).always(function () {
                    il.waiter.hide();
               }).success(function() {
                    row.insertBefore(row.prev());
                });
            });';
            }),
            self::dic()->ui()->factory()->glyph()->sortDescending()->withAdditionalOnLoadCode(function (string $id) : string {
                return '
            $("#' . $id . '").click(function () {
                il.waiter.show();
                var row = $(this).parent().parent();
                $.ajax({
                    url: ' . json_encode(self::dic()
                        ->ctrl()
                        ->getLinkTargetByClass(FieldCtrl::class, FieldCtrl::CMD_MOVE_FIELD_DOWN, "", true)) . ',
                    type: "GET"
                }).always(function () {
                    il.waiter.hide();
                }).success(function() {
                    row.insertAfter(row.next());
                });
        });';
            })
        ]));
        $this->tpl->parseCurrentBlock();

        parent::fillRow($field);

        $this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
            self::dic()->ui()->factory()->link()->standard($this->txt("edit_field"), self::dic()->ctrl()
                ->getLinkTargetByClass(FieldCtrl::class, FieldCtrl::CMD_EDIT_FIELD)),
            self::dic()->ui()->factory()->link()->standard($this->txt("remove_field"), self::dic()->ctrl()
                ->getLinkTargetByClass(FieldCtrl::class, FieldCtrl::CMD_REMOVE_FIELD_CONFIRM))
        ])->withLabel($this->txt("actions"))));
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
