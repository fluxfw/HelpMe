<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ilLink;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\DICTrait;
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\RequiredData\Field\CreatedDateTime\CreatedDateTimeField;
use srag\Plugins\HelpMe\RequiredData\Field\IssueType\IssueTypeField;
use srag\Plugins\HelpMe\RequiredData\Field\Login\LoginField;
use srag\Plugins\HelpMe\RequiredData\Field\PageReference\PageReferenceField;
use srag\Plugins\HelpMe\RequiredData\Field\Project\ProjectField;
use srag\Plugins\HelpMe\RequiredData\Field\Screenshots\ScreenshotsField;
use srag\Plugins\HelpMe\RequiredData\Field\SystemInfos\SystemInfosField;
use srag\Plugins\HelpMe\Support\Recipient\Recipient;
use srag\Plugins\HelpMe\Support\Recipient\Repository as RecipientsRepository;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\Email\EmailField;
use srag\RequiredData\HelpMe\Field\MultilineText\MultilineTextField;
use srag\RequiredData\HelpMe\Field\Select\SelectField;
use srag\RequiredData\HelpMe\Field\Text\TextField;

/**
 * Class Repository
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const GET_PARAM_REF_ID = "ref_id";
    const GET_PARAM_TARGET = "target";
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {
        $this->recipients()->dropTables();
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @return array
     */
    public function getDefaultFields() : array
    {
        return [
            "page_reference"  => [PageReferenceField::getType(), true, null],
            "project"         => [ProjectField::getType(), true, null],
            "issue_type"      => [IssueTypeField::getType(), true, null],
            "title"           => [TextField::getType(), true, null],
            "name"            => [TextField::getType(), true, ConfigFormGUI::KEY_NAME_FIELD],
            "login"           => [LoginField::getType(), true, null],
            "email"           => [EmailField::getType(), true, ConfigFormGUI::KEY_EMAIL_FIELD],
            "phone"           => [TextField::getType(), false, null],
            "priority"        => [SelectField::getType(), true, ConfigFormGUI::KEY_JIRA_PRIORITY_FIELD],
            "description"     => [MultilineTextField::getType(), true, null],
            "reproduce_steps" => [MultilineTextField::getType(), false, null],
            "system_infos"    => [SystemInfosField::getType(), true, null],
            "screenshots"     => [ScreenshotsField::getType(), false, null],
            "createddatetime" => [CreatedDateTimeField::getType(), true, null]
        ];
    }


    /**
     * @param string $recipient_url_key
     *
     * @return string
     */
    public function getLink(string $recipient_url_key = "") : string
    {
        return ILIAS_HTTP_PATH . "/goto.php?target=uihk_" . ilHelpMePlugin::PLUGIN_ID . (!empty($recipient_url_key) ? "_" . $recipient_url_key : "");
    }


    /**
     * @return int|null
     */
    public function getRefId()/*: ?int*/
    {
        $obj_ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);

        if ($obj_ref_id === null) {
            $param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);

            $obj_ref_id = explode("_", $param_target)[1];
        }

        $obj_ref_id = intval($obj_ref_id);

        if ($obj_ref_id > 0) {
            return $obj_ref_id;
        } else {
            return null;
        }
    }


    /**
     * @return string
     */
    public function getRefLink() : string
    {
        $ref_id = $this->getRefId();

        if ($ref_id === null) {
            return "";
        }

        return ilLink::_getStaticLink($ref_id);
    }


    /**
     *
     */
    public function initDefaultFields()/*:void*/
    {
        if (empty(self::helpMe()->requiredData()->fields()->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG))) {

            $old_page_reference = self::helpMe()->config()->getValue(ConfigFormGUI::KEY_PAGE_REFERENCE);
            $old_priorities = self::helpMe()->config()->getValue(ConfigFormGUI::KEY_PRIORITIES);

            foreach ($this->getDefaultFields() as $key => $data) {

                if ($key === "project" && empty(self::helpMe()->projects()->getProjects())) {
                    continue;
                }

                if ($key === "issue_type" && self::helpMe()->config()->getValue(ConfigFormGUI::KEY_RECIPIENT) !== Recipient::CREATE_JIRA_TICKET) {
                    continue;
                }

                if ($key === "page_reference" && !$old_page_reference) {
                    continue;
                }

                if ($key === "priority" && empty($old_priorities)) {
                    continue;
                }

                $field = self::helpMe()->requiredData()->fields()->factory()->newInstance($data[0]);

                $field->setName($key);

                $field->setLabel(self::plugin()->translate($key, SupportGUI::LANG_MODULE, [], true, "de"), "de");
                $field->setLabel(self::plugin()->translate($key, SupportGUI::LANG_MODULE, [], true, "en"), "en");
                $field->setLabel(self::plugin()->translate($key, SupportGUI::LANG_MODULE, [], true, "en"), "default");

                $field->setDescription(self::plugin()->translate($key . "_info", SupportGUI::LANG_MODULE, [], true, "de", ""), "de");
                $field->setDescription(self::plugin()->translate($key . "_info", SupportGUI::LANG_MODULE, [], true, "en", ""), "en");
                $field->setDescription(self::plugin()->translate($key . "_info", SupportGUI::LANG_MODULE, [], true, "en", ""), "default");

                $field->setRequired($data[1]);

                $field->setParentContext(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG);
                $field->setParentId(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG);

                if ($key === "page_reference") {
                    self::helpMe()->config()->removeValue(ConfigFormGUI::KEY_PAGE_REFERENCE);
                }

                if ($key === "priority") {
                    $field->setOptions(array_map(function (string $priority) : array {
                        return [
                            "label" => [
                                "default" => [
                                    "label" => $priority
                                ]
                            ],
                            "value" => $priority
                        ];
                    }, $old_priorities));

                    $old_priorities = [];

                    self::helpMe()->config()->removeValue(ConfigFormGUI::KEY_PRIORITIES);
                }

                self::helpMe()->requiredData()->fields()->storeField($field);

                if (!empty($data[2])) {
                    self::helpMe()->config()->setValue($data[2], $field->getId());
                }
            }
        }
    }


    /**
     * @return JiraCurl
     *
     * @throws ActiveRecordConfigException
     */
    public function initJiraCurl() : JiraCurl
    {
        $jira_curl = new JiraCurl();

        $jira_curl->setJiraDomain(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_DOMAIN));

        $jira_curl->setJiraAuthorization(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_AUTHORIZATION));

        $jira_curl->setJiraUsername(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_USERNAME));
        $jira_curl->setJiraPassword(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_PASSWORD));

        $jira_curl->setJiraConsumerKey(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_CONSUMER_KEY));
        $jira_curl->setJiraPrivateKey(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_PRIVATE_KEY));
        $jira_curl->setJiraAccessToken(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_ACCESS_TOKEN));

        return $jira_curl;
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        $this->recipients()->installTables();

        $this->initDefaultFields();

        $old_info = self::helpMe()->config()->getValue(ConfigFormGUI::KEY_INFO);
        if (!empty($old_info)) {
            self::helpMe()->config()->setValue(ConfigFormGUI::KEY_INFO_TEXTS, [
                "default" => [
                    ConfigFormGUI::KEY_INFO_TEXT => $old_info
                ]
            ]);

            self::helpMe()->config()->removeValue(ConfigFormGUI::KEY_INFO);
        }
    }


    /**
     * @return RecipientsRepository
     */
    public function recipients() : RecipientsRepository
    {
        return RecipientsRepository::getInstance();
    }
}
