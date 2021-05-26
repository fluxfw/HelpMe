<?php

namespace srag\RequiredData\HelpMe\Field\Table;

use srag\DataTableUI\HelpMe\Component\Table;
use srag\DataTableUI\HelpMe\Implementation\Utils\AbstractTableBuilder;
use srag\RequiredData\HelpMe\Field\Field\Group\GroupCtrl;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class TableBuilder
 *
 * @package srag\RequiredData\HelpMe\Field\Table
 */
class TableBuilder extends AbstractTableBuilder
{

    use RequiredDataTrait;

    /**
     * @inheritDoc
     *
     * @param FieldsCtrl $parent
     */
    public function __construct(FieldsCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard(self::requiredData()->getPlugin()->translate("add_field", FieldsCtrl::LANG_MODULE),
            self::dic()->ctrl()->getLinkTargetByClass($this->parent->getFieldCtrlClass(), FieldCtrl::CMD_ADD_FIELD)));

        return parent::render();
    }


    /**
     * @inheritDoc
     */
    protected function buildTable() : Table
    {
        $columns = [
            self::dataTableUI()->column()->column("sort", "")->withFormatter(self::dataTableUI()->column()->formatter()->actions()->sort()),
            self::dataTableUI()->column()->column("enabled",
                self::requiredData()->getPlugin()->translate("enabled", FieldsCtrl::LANG_MODULE))->withSortable(false)->withFormatter(self::dataTableUI()->column()->formatter()->check())
        ];

        if (self::requiredData()->isEnableNames()) {
            $columns[] = self::dataTableUI()->column()->column("name",
                self::requiredData()->getPlugin()->translate("name", FieldsCtrl::LANG_MODULE))->withSortable(false);
        }

        $columns = array_merge($columns, [
            self::dataTableUI()->column()->column("type_title",
                self::requiredData()->getPlugin()->translate("type", FieldsCtrl::LANG_MODULE))->withSortable(false),
            self::dataTableUI()->column()->column("required",
                self::requiredData()->getPlugin()->translate("required", FieldsCtrl::LANG_MODULE))->withSortable(false)->withFormatter(self::dataTableUI()->column()->formatter()->check()),
            self::dataTableUI()->column()->column("label",
                self::requiredData()->getPlugin()->translate("label", FieldsCtrl::LANG_MODULE))->withSortable(false),
            self::dataTableUI()->column()->column("description",
                self::requiredData()->getPlugin()->translate("description", FieldsCtrl::LANG_MODULE))->withSortable(false),
            self::dataTableUI()->column()->column("field_description",
                self::requiredData()->getPlugin()->translate("field_description", FieldsCtrl::LANG_MODULE))->withSortable(false),
            self::dataTableUI()->column()->column("actions",
                self::requiredData()->getPlugin()->translate("actions", FieldsCtrl::LANG_MODULE))->withFormatter(self::dataTableUI()->column()->formatter()->actions()->actionsDropdown())
        ]);

        $multiple_actions = [
            self::requiredData()->getPlugin()->translate("enable_fields", FieldsCtrl::LANG_MODULE)  => self::dic()
                ->ctrl()
                ->getLinkTarget($this->parent, FieldsCtrl::CMD_ENABLE_FIELDS, "", false, false),
            self::requiredData()->getPlugin()->translate("disable_fields", FieldsCtrl::LANG_MODULE) => self::dic()
                ->ctrl()
                ->getLinkTarget($this->parent, FieldsCtrl::CMD_DISABLE_FIELD, "", false, false),
            self::requiredData()->getPlugin()->translate("remove_fields", FieldsCtrl::LANG_MODULE)  => self::dic()
                ->ctrl()
                ->getLinkTarget($this->parent, FieldsCtrl::CMD_REMOVE_FIELDS_CONFIRM, "", false, false)
        ];
        if (self::requiredData()->isEnableGroups() && !($this->parent instanceof GroupCtrl)) {
            $multiple_actions[self::requiredData()->getPlugin()->translate("create_group_of_fields", FieldsCtrl::LANG_MODULE)] = self::dic()
                ->ctrl()
                ->getLinkTarget($this->parent, FieldsCtrl::CMD_CREATE_GROUP_OF_FIELDS, "", false, false);
        }

        $table = self::dataTableUI()->table("fields_" . self::requiredData()->getPlugin()->getPluginObject()->getId(),
            self::dic()->ctrl()->getLinkTarget($this->parent, FieldsCtrl::CMD_LIST_FIELDS, "", false, false),
            self::requiredData()->getPlugin()->translate("fields", FieldsCtrl::LANG_MODULE), $columns, new DataFetcher($this->parent))
            ->withPlugin(self::requiredData()->getPlugin())
            ->withMultipleActions($multiple_actions);

        return $table;
    }
}
