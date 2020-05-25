<?php

namespace srag\Plugins\HelpMe\Support\Form;

use ilHelpMePlugin;
use srag\CustomInputGUIs\HelpMe\FormBuilder\AbstractFormBuilder;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\Form\IssueTypeSelectInputGUI;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\IssueTypeField;
use srag\Plugins\HelpMe\RequiredData\Field\Project\Form\ProjectSelectInputGUI;
use srag\Plugins\HelpMe\RequiredData\Field\Project\ProjectField;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class SupportFormBuilder
 *
 * @package srag\Plugins\HelpMe\Support\Form
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SupportFormBuilder extends AbstractFormBuilder
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var Support
     */
    protected $support;


    /**
     * SupportFormBuilder constructor
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
    protected function getAction() : string
    {
        return self::dic()->ctrl()->getFormAction($this->parent, "", "", true);
    }


    /**
     * @inheritDoc
     */
    protected function getButtons() : array
    {
        $buttons = [];

        return $buttons;
    }


    /**
     * @inheritDoc
     */
    protected function getData() : array
    {
        $data = [];

        foreach (array_keys($this->getFields()) as $key) {
            $field_id = substr($key, strlen("field_"));
            $data[$key] = $this->support->getFieldValueById($field_id, null);
        }

        return $data;
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        $fields = self::helpMe()->requiredData()->fills()->getFormFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG);

        return $fields;
    }


    /**
     * @inheritDoc
     */
    protected function getTitle() : string
    {
        return "";
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        return self::output()->getHTML([
            '<div id="form_helpme_form">',
            parent::render(),
            '</div>'
        ]);
    }


    /**
     * @inheritDoc
     */
    protected function setButtonsToForm(string $html) : string
    {
        $first = true;

        $html = preg_replace_callback(self::REPLACE_BUTTONS_REG_EXP, function (array $matches) use (&$first) : string {
            if ($first) {
                $first = false;

                return "";
            } else {
                return '<input class="btn btn-default btn-sm" type="submit" name="cmd[' . SupportGUI::CMD_NEW_SUPPORT . ']" value="' . self::plugin()->translate("submit", SupportGUI::LANG_MODULE)
                    . '" id="helpme_submit">&nbsp;<input class="btn btn-default btn-sm" type="submit" name="cmd[]" value="' . self::plugin()->translate("cancel", SupportGUI::LANG_MODULE)
                    . '" id="helpme_cancel">';
            }
        }, $html);

        return $html;
    }


    /**
     * @inheritDoc
     */
    protected function storeData(array $data) : void
    {
        foreach (array_keys($this->getFields()) as $key) {
            $field_id = substr($key, strlen("field_"));
            $this->support->setFieldValueById($field_id, $data[$key]);
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
