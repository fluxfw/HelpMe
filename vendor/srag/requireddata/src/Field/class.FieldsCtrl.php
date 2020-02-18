<?php

namespace srag\RequiredData\HelpMe\Field;

use ilConfirmationGUI;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class FieldsCtrl
 *
 * @package srag\RequiredData\HelpMe\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class FieldsCtrl
{

    use DICTrait;
    use RequiredDataTrait;
    const CMD_DISABLE_FIELD = "disableFields";
    const CMD_ENABLE_FIELDS = "enableFields";
    const CMD_LIST_FIELDS = "listFields";
    const CMD_REMOVE_FIELDS = "removeFields";
    const CMD_REMOVE_FIELDS_CONFIRM = "removeFieldsConfirm";
    const LANG_MODULE = "required_data";
    const TAB_LIST_FIELDS = "list_fields";
    /**
     * @var int
     */
    protected $parent_context;
    /**
     * @var int
     */
    protected $parent_id;


    /**
     * FieldsCtrl constructor
     *
     * @param int $parent_context
     * @param int $parent_id
     */
    public function __construct(int $parent_context, int $parent_id)
    {
        $this->parent_context = $parent_context;
        $this->parent_id = $parent_id;
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(FieldCtrl::class):
                self::dic()->ctrl()->forwardCommand(new FieldCtrl($this));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_DISABLE_FIELD:
                    case self::CMD_ENABLE_FIELDS:
                    case self::CMD_LIST_FIELDS:
                    case self::CMD_REMOVE_FIELDS:
                    case self::CMD_REMOVE_FIELDS_CONFIRM:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_LIST_FIELDS);
    }


    /**
     *
     */
    protected function listFields()/*: void*/
    {
        $table = self::requiredData()->fields()->factory()->newTableInstance($this);

        self::output()->output($table);
    }


    /**
     *
     */
    protected function enableFields()/*: void*/
    {
        $field_ids = filter_input(INPUT_POST, FieldCtrl::GET_PARAM_FIELD_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        if (!is_array($field_ids)) {
            $field_ids = [];
        }

        /**
         * @var AbstractField[] $fields
         */
        $fields = array_map(function (string $field_id) : AbstractField {
            list($type, $field_id) = explode("_", $field_id);

            return self::requiredData()->fields()->getFieldById($this->parent_context, $this->parent_id, $type, $field_id);
        }, $field_ids);

        foreach ($fields as $field) {
            $field->setEnabled(true);

            $field->store();
        }

        ilUtil::sendSuccess(self::requiredData()->getPlugin()->translate("enabled_fields", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_FIELDS);
    }


    /**
     *
     */
    protected function disableFields()/*: void*/
    {
        $field_ids = filter_input(INPUT_POST, FieldCtrl::GET_PARAM_FIELD_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        if (!is_array($field_ids)) {
            $field_ids = [];
        }

        /**
         * @var AbstractField[] $fields
         */
        $fields = array_map(function (string $field_id) : AbstractField {
            list($type, $field_id) = explode("_", $field_id);

            return self::requiredData()->fields()->getFieldById($this->parent_context, $this->parent_id, $type, $field_id);
        }, $field_ids);

        foreach ($fields as $field) {
            $field->setEnabled(false);

            $field->store();
        }

        ilUtil::sendSuccess(self::requiredData()->getPlugin()->translate("disabled_fields", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_FIELDS);
    }


    /**
     *
     */
    protected function removeFieldsConfirm()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_LIST_FIELDS);

        $field_ids = filter_input(INPUT_POST, FieldCtrl::GET_PARAM_FIELD_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        if (!is_array($field_ids)) {
            $field_ids = [];
        }

        /**
         * @var AbstractField[] $fields
         */
        $fields = array_map(function (string $field_id) : AbstractField {
            list($type, $field_id) = explode("_", $field_id);

            return self::requiredData()->fields()->getFieldById($this->parent_context, $this->parent_id, $type, $field_id);
        }, $field_ids);

        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::requiredData()->getPlugin()->translate("remove_fields_confirm", self::LANG_MODULE));

        foreach ($fields as $field) {
            $confirmation->addItem(FieldCtrl::GET_PARAM_FIELD_ID . "[]", $field->getId(), $field->getFieldTitle());
        }

        $confirmation->setConfirm(self::requiredData()->getPlugin()->translate("remove", self::LANG_MODULE), self::CMD_REMOVE_FIELDS);
        $confirmation->setCancel(self::requiredData()->getPlugin()->translate("cancel", self::LANG_MODULE), self::CMD_LIST_FIELDS);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function removeFields()/*: void*/
    {
        $field_ids = filter_input(INPUT_POST, FieldCtrl::GET_PARAM_FIELD_ID, FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        if (!is_array($field_ids)) {
            $field_ids = [];
        }

        /**
         * @var AbstractField[] $fields
         */
        $fields = array_map(function (string $field_id) : AbstractField {
            list($type, $field_id) = explode("_", $field_id);

            return self::requiredData()->fields()->getFieldById($this->parent_context, $this->parent_id, $type, $field_id);
        }, $field_ids);

        foreach ($fields as $field) {
            self::requiredData()->fields()->deleteField($field);
        }

        ilUtil::sendSuccess(self::requiredData()->getPlugin()->translate("removed_fields", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_FIELDS);
    }


    /**
     * @return int
     */
    public function getParentContext() : int
    {
        return $this->parent_context;
    }


    /**
     * @return int
     */
    public function getParentId() : int
    {
        return $this->parent_id;
    }
}
