<?php

namespace srag\RequiredData\HelpMe\Field\Field\Group;

use srag\RequiredData\HelpMe\Field\AbstractField;
use srag\RequiredData\HelpMe\Field\FieldCtrl;
use srag\RequiredData\HelpMe\Field\FieldsCtrl;

/**
 * Class GroupField
 *
 * @package srag\RequiredData\HelpMe\Field\Field\Group
 */
class GroupField extends AbstractField
{

    const PARENT_CONTEXT_FIELD_GROUP = 1000;
    const TABLE_NAME_SUFFIX = "group";


    /**
     * @inheritDoc
     */
    public function getActions() : array
    {
        return array_merge(parent::getActions(), [
            self::dic()->ui()->factory()->link()->standard(self::requiredData()->getPlugin()->translate("ungroup", FieldsCtrl::LANG_MODULE),
                self::dic()->ctrl()->getLinkTargetByClass($this->getFieldCtrlClass(), FieldCtrl::CMD_UNGROUP))
        ]);
    }


    /**
     * @inheritDoc
     */
    public function getFieldDescription() : string
    {
        $descriptions = array_map(function (AbstractField $field) : string {
            return $field->getFieldTitle();
        }, self::requiredData()->fields()->getFields(self::PARENT_CONTEXT_FIELD_GROUP, $this->field_id));

        return nl2br(implode("\n", array_map(function (string $description) : string {
            return htmlspecialchars($description);
        }, $descriptions)), false);
    }
}
