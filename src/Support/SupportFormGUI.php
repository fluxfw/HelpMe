<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form\IssueTypeSelectInputGUI;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\IssueTypeField;
use srag\Plugins\HelpMe\RequiredData\Field\Project\Form\ProjectSelectInputGUI;
use srag\Plugins\HelpMe\RequiredData\Field\Project\ProjectField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class SupportFormGUI
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SupportFormGUI extends PropertyFormGUI
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const LANG_MODULE = SupportGUI::LANG_MODULE;
    /**
     * @var Support
     */
    protected $support;


    /**
     * SupportFormGUI constructor
     *
     * @param SupportGUI $parent
     * @param Support    $support
     */
    public function __construct(SupportGUI $parent, Support $support)
    {
        $this->support = $support;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch (true) {
            case (strpos($key, "field_") === 0):
                $field_id = substr($key, strlen("field_"));

                return $this->support->getFieldValueById($field_id, null);

            default:
                return Items::getter($this->support, $key);
        }
    }


    /**
     * @inheritDoc
     */
    protected final function initAction() : void
    {
        $this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));
    }


    /**
     * @inheritDoc
     */
    protected function initCommands() : void
    {
        $this->addCommandButton(SupportGUI::CMD_NEW_SUPPORT, $this->txt("submit"), "helpme_submit");

        $this->addCommandButton("", $this->txt("cancel"), "helpme_cancel");

        $this->setShowTopButtons(false);
    }


    /**
     * @inheritDoc
     */
    protected function initFields() : void
    {
        $this->fields = self::helpMe()->requiredData()->fills()->getFormFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG);
    }


    /**
     * @inheritDoc
     */
    protected final function initId() : void
    {
        $this->setId("helpme_form");
    }


    /**
     * @inheritDoc
     */
    protected final function initTitle() : void
    {
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value) : void
    {
        switch ($key) {
            case (strpos($key, "field_") === 0):
                $field_id = substr($key, strlen("field_"));

                $this->support->setFieldValueById($field_id, $value);
                break;

            default:
                Items::setter($this->support, $key, $value);
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

        $this->support->setFieldValues(self::helpMe()
            ->requiredData()
            ->fills()
            ->formatAsJsons(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, $this->support->getFieldValues()));

        return true;
    }


    /**
     * @return ProjectSelectInputGUI|null
     */
    public function extractProjectSelector() : ?ProjectSelectInputGUI
    {
        $field = current(self::helpMe()
            ->requiredData()
            ->fields()
            ->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [ProjectField::getType()]));

        if ($field) {
            $item = $this->getItemByPostVar("field_" . $field->getId());
            if ($item !== false) {
                return $item;
            }
        }

        return null;
    }


    /**
     * @return IssueTypeSelectInputGUI|null
     */
    public function extractIssueTypeSelector() : ?IssueTypeSelectInputGUI
    {
        $field = current(self::helpMe()
            ->requiredData()
            ->fields()
            ->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [IssueTypeField::getType()]));

        if ($field) {
            $item = $this->getItemByPostVar("field_" . $field->getId());
            if ($item !== false) {
                return $item;
            }
        }

        return null;
    }
}
