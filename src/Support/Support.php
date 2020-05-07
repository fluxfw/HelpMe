<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ILIAS\FileUpload\DTO\UploadResult;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Project\Project;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\IssueTypeField;
use srag\Plugins\HelpMe\RequiredData\Field\Project\ProjectField;
use srag\Plugins\HelpMe\RequiredData\Field\Screenshots\ScreenshotsField;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Support
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Support
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const REQUIRED_DATA_PARENT_CONTEXT_CONFIG = 1;
    /**
     * @var array
     */
    protected $field_values = [];


    /**
     * Support constructor
     */
    public function __construct()
    {
        if (self::helpMe()->ilias()->users()->getUserId() !== intval(ANONYMOUS_USER_ID)) {
            if (!empty(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_NAME_FIELD))) {
                $this->field_values[self::helpMe()->config()->getValue(ConfigFormGUI::KEY_NAME_FIELD)] = self::dic()->user()->getFullname();
            }

            if (!empty(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_EMAIL_FIELD))) {
                $this->field_values[self::helpMe()->config()->getValue(ConfigFormGUI::KEY_EMAIL_FIELD)] = self::dic()->user()->getEmail();
            }
        }
    }


    /**
     * @return array
     */
    public function getFieldValues() : array
    {
        return $this->field_values;
    }


    /**
     * @param string $field_id
     * @param mixed  $default_value
     *
     * @return mixed
     */
    public function getFieldValueById(string $field_id, $default_value)
    {
        $field_value = $this->field_values[$field_id];

        if (empty($field_value)) {
            $field_value = $default_value;
        }

        return $field_value;
    }


    /**
     * @param string $config_key
     * @param mixed  $default_value
     *
     * @return mixed
     */
    public function getFieldValueByConfigKey(string $config_key, $default_value)
    {
        return $this->getFieldValueById(self::helpMe()->config()->getValue($config_key), $default_value);
    }


    /**
     * @param string $name
     * @param mixed  $default_value
     *
     * @return mixed
     */
    public function getFieldValueByName(string $name, $default_value)
    {
        $field = self::helpMe()->requiredData()->fields()->getFieldByName(self::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, self::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, $name);

        if ($field !== null) {
            return $this->getFieldValueById($field->getId(), $default_value);
        } else {
            return $default_value;
        }
    }


    /**
     * @param string $type
     * @param mixed  $default_value
     *
     * @return mixed
     */
    public function getFieldValueByType(string $type, $default_value)
    {
        $field = current(self::helpMe()
            ->requiredData()
            ->fields()
            ->getFields(self::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, self::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [$type]));

        if ($field) {
            return $this->getFieldValueById($field->getId(), $default_value);
        } else {
            return $default_value;
        }
    }


    /**
     * @param array $field_values
     */
    public function setFieldValues(array $field_values)/* : void*/
    {
        $this->field_values = $field_values;
    }


    /**
     * @param string $field_id
     * @param mixed  $value
     */
    public function setFieldValueById(string $field_id, $value)/* : void*/
    {
        $this->field_values[$field_id] = $value;
    }


    /**
     * @return array
     */
    public function getFormattedFieldValues() : array
    {
        return self::helpMe()
            ->requiredData()
            ->fills()
            ->formatAsStrings(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, $this->field_values, true);
    }


    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->getFieldValueByConfigKey(ConfigFormGUI::KEY_EMAIL_FIELD, (self::helpMe()->ilias()->users()->getUserId() !== intval(ANONYMOUS_USER_ID) ? self::dic()->user()->getEmail() : ""));
    }


    /**
     * @return string
     */
    public function getFixVersion() : string
    {
        return self::helpMe()->projects()->getFixVersionForIssueType($this->getProject(), $this->getIssueType());
    }


    /**
     * @return string
     */
    public function getIssueType() : string
    {
        return $this->getFieldValueByType(IssueTypeField::getType(), "");
    }


    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->getFieldValueByConfigKey(ConfigFormGUI::KEY_NAME_FIELD, (self::helpMe()->ilias()->users()->getUserId() !== intval(ANONYMOUS_USER_ID) ? self::dic()->user()->getFullname() : ""));
    }


    /**
     * @return string
     */
    public function getPriority() : string
    {
        return $this->getFieldValueByConfigKey(ConfigFormGUI::KEY_JIRA_PRIORITY_FIELD, "");
    }


    /**
     * @return Project|null
     */
    public function getProject()/* : ?Project*/
    {
        return self::helpMe()->projects()->getProjectByUrlKey($this->getFieldValueByType(ProjectField::getType(), ""));
    }


    /**
     * @return UploadResult[]
     */
    public function getScreenshots() : array
    {
        return $this->getFieldValueByType(ScreenshotsField::getType(), []);
    }


    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->getFieldValueByName($name, "");
    }
}
