<?php

namespace srag\RequiredData\HelpMe\Field;

require_once __DIR__ . "/../../../../autoload.php";

use ilConfirmationGUI;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;
use srag\RequiredData\HelpMe\Field\Field\Group\GroupField;
use srag\RequiredData\HelpMe\Field\Field\Group\GroupsCtrl;
use srag\RequiredData\HelpMe\Field\Field\StaticMultiSearchSelect\SMSSAjaxAutoCompleteCtrl;
use srag\RequiredData\HelpMe\Utils\RequiredDataTrait;

/**
 * Class FieldCtrl
 *
 * @package           srag\RequiredData\HelpMe\Field
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\RequiredData\HelpMe\Field\FieldCtrl: srag\RequiredData\HelpMe\Field\FieldsCtrl
 */
class FieldCtrl
{

    use DICTrait;
    use RequiredDataTrait;

    const CMD_ADD_FIELD = "addField";
    const CMD_BACK = "back";
    const CMD_CREATE_FIELD = "createField";
    const CMD_EDIT_FIELD = "editField";
    const CMD_MOVE_FIELD_DOWN = "moveFieldDown";
    const CMD_MOVE_FIELD_UP = "moveFieldUp";
    const CMD_REMOVE_FIELD = "removeField";
    const CMD_REMOVE_FIELD_CONFIRM = "removeFieldConfirm";
    const CMD_UNGROUP = "ungroup";
    const CMD_UPDATE_FIELD = "updateField";
    const GET_PARAM_FIELD_ID = "field_id_";
    const GET_PARAM_FIELD_TYPE = "field_type_";
    const TAB_EDIT_FIELD = "field_data";
    /**
     * @var AbstractField|null
     */
    protected $field;
    /**
     * @var FieldsCtrl
     */
    protected $parent;


    /**
     * FieldCtrl constructor
     *
     * @param FieldsCtrl $parent
     */
    public function __construct(FieldsCtrl $parent)
    {
        $this->parent = $parent;
    }


    /**
     *
     */
    public function executeCommand() : void
    {
        $this->field = self::requiredData()
            ->fields()
            ->getFieldById($this->parent->getParentContext(), $this->parent->getParentId(), strval(filter_input(INPUT_GET, self::GET_PARAM_FIELD_TYPE . $this->parent->getParentContext())),
                intval(filter_input(INPUT_GET, self::GET_PARAM_FIELD_ID . $this->parent->getParentContext())));

        if ($this->field !== null) {
            self::dic()->ctrl()->setParameter($this, self::GET_PARAM_FIELD_TYPE . $this->parent->getParentContext(), $this->field->getType());
            self::dic()->ctrl()->setParameter($this, self::GET_PARAM_FIELD_ID . $this->parent->getParentContext(), $this->field->getFieldId());
        }

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(GroupsCtrl::class):
                self::dic()->ctrl()->forwardCommand(new GroupsCtrl(GroupField::PARENT_CONTEXT_FIELD_GROUP, $this->field->getFieldId()));
                break;

            case strtolower(SMSSAjaxAutoCompleteCtrl::class):
                self::dic()->ctrl()->forwardCommand(new SMSSAjaxAutoCompleteCtrl($this));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_FIELD:
                    case self::CMD_BACK:
                    case self::CMD_CREATE_FIELD:
                    case self::CMD_EDIT_FIELD:
                    case self::CMD_MOVE_FIELD_DOWN:
                    case self::CMD_MOVE_FIELD_UP:
                    case self::CMD_REMOVE_FIELD:
                    case self::CMD_REMOVE_FIELD_CONFIRM:
                    case self::CMD_UNGROUP:
                    case self::CMD_UPDATE_FIELD:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @return AbstractField
     */
    public function getField() : AbstractField
    {
        return $this->field;
    }


    /**
     * @return FieldsCtrl
     */
    public function getParent() : FieldsCtrl
    {
        return $this->parent;
    }


    /**
     *
     */
    protected function addField() : void
    {
        $form = self::requiredData()->fields()->factory()->newCreateFormBuilderInstance($this);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function back() : void
    {
        self::dic()->ctrl()->redirect($this->parent, FieldsCtrl::CMD_LIST_FIELDS);
    }


    /**
     *
     */
    protected function createField() : void
    {
        $form = self::requiredData()->fields()->factory()->newCreateFormBuilderInstance($this);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        $this->field = $form->getField();

        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_FIELD_TYPE . $this->parent->getParentContext(), $this->field->getType());
        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_FIELD_ID . $this->parent->getParentContext(), $this->field->getFieldId());

        ilUtil::sendSuccess(self::requiredData()->getPlugin()->translate("added_field", FieldsCtrl::LANG_MODULE, [$this->field->getFieldTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_FIELD);
    }


    /**
     *
     */
    protected function editField() : void
    {
        $form = self::requiredData()->fields()->factory()->newFormBuilderInstance($this, $this->field);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function moveFieldDown()
    {
        self::requiredData()->fields()->moveFieldDown($this->field);

        exit;
    }


    /**
     *
     */
    protected function moveFieldUp()
    {
        self::requiredData()->fields()->moveFieldUp($this->field);

        exit;
    }


    /**
     *
     */
    protected function removeField() : void
    {
        self::requiredData()->fields()->deleteField($this->field);

        ilUtil::sendSuccess(self::requiredData()->getPlugin()->translate("removed_field", FieldsCtrl::LANG_MODULE, [$this->field->getFieldTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function removeFieldConfirm() : void
    {
        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::requiredData()->getPlugin()
            ->translate("remove_field_confirm", FieldsCtrl::LANG_MODULE, [$this->field->getFieldTitle()]));

        $confirmation->addItem(self::GET_PARAM_FIELD_ID . $this->parent->getParentContext(), $this->field->getId(), $this->field->getFieldTitle());

        $confirmation->setConfirm(self::requiredData()->getPlugin()->translate("remove", FieldsCtrl::LANG_MODULE), self::CMD_REMOVE_FIELD);
        $confirmation->setCancel(self::requiredData()->getPlugin()->translate("cancel", FieldsCtrl::LANG_MODULE), self::CMD_BACK);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function setTabs() : void
    {
        self::dic()->tabs()->clearTargets();

        self::dic()->tabs()->setBackTarget(self::requiredData()->getPlugin()->translate("fields", FieldsCtrl::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK));

        if ($this->field !== null) {
            if (self::dic()->ctrl()->getCmd() === self::CMD_REMOVE_FIELD_CONFIRM) {
                self::dic()->tabs()->addTab(self::TAB_EDIT_FIELD, self::requiredData()->getPlugin()->translate("remove_field", FieldsCtrl::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_REMOVE_FIELD_CONFIRM));
            } else {
                self::dic()->tabs()->addTab(self::TAB_EDIT_FIELD, self::requiredData()->getPlugin()->translate("edit_field", FieldsCtrl::LANG_MODULE), self::dic()->ctrl()
                    ->getLinkTarget($this, self::CMD_EDIT_FIELD));

                self::dic()->locator()->addItem($this->field->getFieldTitle(), self::dic()->ctrl()->getLinkTarget($this, self::CMD_EDIT_FIELD));
            }
        } else {
            self::dic()->tabs()->addTab(self::TAB_EDIT_FIELD, self::requiredData()->getPlugin()->translate("add_field", FieldsCtrl::LANG_MODULE), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_ADD_FIELD));
        }

        self::dic()->tabs()->activateTab(self::TAB_EDIT_FIELD);
    }


    /**
     *
     */
    protected function ungroup() : void
    {
        self::requiredData()->fields()->ungroup($this->field);

        ilUtil::sendSuccess(self::requiredData()->getPlugin()->translate("removed_field", FieldsCtrl::LANG_MODULE, [$this->field->getFieldTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function updateField() : void
    {
        $form = self::requiredData()->fields()->factory()->newFormBuilderInstance($this, $this->field);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::requiredData()->getPlugin()->translate("saved_field", FieldsCtrl::LANG_MODULE, [$this->field->getFieldTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_FIELD);
    }
}
