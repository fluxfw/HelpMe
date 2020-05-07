<?php

namespace srag\Plugins\HelpMe\Config;

use ilCheckboxInputGUI;
use ilEMailInputGUI;
use ilHelpMePlugin;
use ilNumberInputGUI;
use ilPasswordInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\TabsInputGUI;
use srag\CustomInputGUIs\HelpMe\TextAreaInputGUI\TextAreaInputGUI;
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationInterface;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl;
use srag\Plugins\HelpMe\Support\Recipient\Recipient;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\RequiredData\HelpMe\Field\AbstractField;
use srag\RequiredData\HelpMe\Field\Email\EmailField;
use srag\RequiredData\HelpMe\Field\Radio\RadioField;
use srag\RequiredData\HelpMe\Field\SearchSelect\SearchSelectField;
use srag\RequiredData\HelpMe\Field\Select\SelectField;
use srag\RequiredData\HelpMe\Field\Text\TextField;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends PropertyFormGUI
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const KEY_EMAIL_FIELD = "email_field";
    /**
     * @var string
     *
     * @deprecated
     */
    const KEY_INFO = "info";
    const KEY_INFO_TEXTS = "info_texts";
    const KEY_INFO_TEXT = "info_text";
    const KEY_JIRA_ACCESS_TOKEN = "jira_access_token";
    const KEY_JIRA_AUTHORIZATION = "jira_authorization";
    const KEY_JIRA_CONSUMER_KEY = "jira_consumer_key";
    const KEY_JIRA_CREATE_SERVICE_DESK_REQUEST = "jira_create_service_desk_request";
    const KEY_JIRA_DOMAIN = "jira_domain";
    const KEY_JIRA_PASSWORD = "jira_password";
    const KEY_JIRA_PRIORITY_FIELD = "jira_priority_field";
    const KEY_JIRA_PRIVATE_KEY = "jira_private_key";
    const KEY_JIRA_SERVICE_DESK_CREATE_AS_CUSTOMER = "jira_service_desk_create_as_customer";
    const KEY_JIRA_SERVICE_DESK_CREATE_NEW_CUSTOMERS = "jira_service_desk_create_new_customers";
    const KEY_JIRA_SERVICE_DESK_ID = "jira_service_desk_id";
    const KEY_JIRA_SERVICE_DESK_LINK_TYPE = "jira_service_desk_link_type";
    const KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID = "jira_service_desk_request_type_id";
    const KEY_JIRA_USERNAME = "jira_username";
    const KEY_NAME_FIELD = "name_field";
    /**
     * @var string
     *
     * @deprecated
     */
    const KEY_PAGE_REFERENCE = "page_reference";
    /**
     * @var string
     *
     * @deprecated
     */
    const KEY_PRIORITIES = "priorities";
    const KEY_RECIPIENT = "recipient";
    const KEY_RECIPIENT_TEMPLATES = "recipient_templates";
    const KEY_ROLES = "roles";
    const KEY_SEND_CONFIRMATION_EMAIL = "send_confirmation_email";
    const KEY_SEND_EMAIL_ADDRESS = "send_email_address";
    const KEY_USAGE_HIDDEN = "usage_hidden";
    const LANG_MODULE = ConfigCtrl::LANG_MODULE;


    /**
     * ConfigFormGUI constructor
     *
     * @param ConfigCtrl $parent
     */
    public function __construct(ConfigCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch (true) {
            case (strpos($key, self::KEY_RECIPIENT_TEMPLATES . "_") === 0):
                $template_name = substr($key, strlen(self::KEY_RECIPIENT_TEMPLATES) + 1);

                return self::helpMe()->config()->getValue(self::KEY_RECIPIENT_TEMPLATES)[$template_name];

            case ($key === self::KEY_SEND_CONFIRMATION_EMAIL . "_always_enabled"):
                return true;

            default:
                return self::helpMe()->config()->getValue($key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(ConfigCtrl::CMD_UPDATE_CONFIGURE, $this->txt("save"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            self::KEY_NAME_FIELD                                  => [
                self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_OPTIONS  => ["" => ""] + array_map(function (AbstractField $field) : string {
                        return $field->getLabel();
                    }, self::helpMe()->requiredData()->fields()->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [
                        EmailField::getType(),
                        TextField::getType()
                    ]))
            ],
            self::KEY_EMAIL_FIELD                                 => [
                self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_OPTIONS  => ["" => ""] + array_map(function (AbstractField $field) : string {
                        return $field->getLabel();
                    }, self::helpMe()->requiredData()->fields()->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [
                        EmailField::getType()
                    ]))
            ],
            self::KEY_RECIPIENT                                   => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_SUBITEMS => [
                    Recipient::SEND_EMAIL         => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                                self::KEY_SEND_EMAIL_ADDRESS => [
                                    self::PROPERTY_CLASS    => ilEMailInputGUI::class,
                                    self::PROPERTY_REQUIRED => true
                                ]
                            ] + $this->getTemplateSelection(Recipient::SEND_EMAIL)
                    ],
                    Recipient::CREATE_JIRA_TICKET => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                                self::KEY_JIRA_DOMAIN         => [
                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                    self::PROPERTY_REQUIRED => true
                                ],
                                self::KEY_JIRA_AUTHORIZATION  => [
                                    self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                                    self::PROPERTY_REQUIRED => true,
                                    self::PROPERTY_SUBITEMS => [
                                        JiraCurl::AUTHORIZATION_USERNAMEPASSWORD => [
                                            self::PROPERTY_CLASS    => ilRadioOption::class,
                                            self::PROPERTY_SUBITEMS => [
                                                self::KEY_JIRA_USERNAME => [
                                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ],
                                                self::KEY_JIRA_PASSWORD => [
                                                    self::PROPERTY_CLASS    => ilPasswordInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true,
                                                    "setRetype"             => false
                                                ]
                                            ]
                                        ],
                                        JiraCurl::AUTHORIZATION_OAUTH            => [
                                            self::PROPERTY_CLASS    => ilRadioOption::class,
                                            self::PROPERTY_SUBITEMS => [
                                                self::KEY_JIRA_CONSUMER_KEY => [
                                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ],
                                                self::KEY_JIRA_PRIVATE_KEY  => [
                                                    self::PROPERTY_CLASS    => ilTextAreaInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ],
                                                self::KEY_JIRA_ACCESS_TOKEN => [
                                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                self::KEY_JIRA_PRIORITY_FIELD => [
                                    self::PROPERTY_CLASS   => ilSelectInputGUI::class,
                                    self::PROPERTY_OPTIONS => ["" => ""] + array_map(function (AbstractField $field) : string {
                                            return $field->getLabel();
                                        }, self::helpMe()->requiredData()->fields()->getFields(Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, Support::REQUIRED_DATA_PARENT_CONTEXT_CONFIG, [
                                            RadioField::getType(),
                                            SearchSelectField::getType(),
                                            SelectField::getType()
                                        ]))
                                ]
                            ] + $this->getTemplateSelection(Recipient::CREATE_JIRA_TICKET) + [
                                self::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST => [
                                    self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                                    self::PROPERTY_SUBITEMS => [
                                        self::KEY_JIRA_SERVICE_DESK_ID                 => [
                                            self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                            self::PROPERTY_REQUIRED => true
                                        ],
                                        self::KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID    => [
                                            self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                            self::PROPERTY_REQUIRED => true
                                        ],
                                        self::KEY_JIRA_SERVICE_DESK_CREATE_AS_CUSTOMER => [
                                            self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                                            self::PROPERTY_SUBITEMS => [
                                                self::KEY_JIRA_SERVICE_DESK_CREATE_NEW_CUSTOMERS => [
                                                    self::PROPERTY_CLASS => ilCheckboxInputGUI::class
                                                ]
                                            ]
                                        ],
                                        self::KEY_JIRA_SERVICE_DESK_LINK_TYPE          => [
                                            self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                            self::PROPERTY_REQUIRED => true
                                        ]
                                    ]
                                ]
                            ]
                    ]
                ]
            ],
            self::KEY_SEND_CONFIRMATION_EMAIL                     => [
                self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                self::PROPERTY_SUBITEMS => $this->getTemplateSelection(self::KEY_SEND_CONFIRMATION_EMAIL),
                self::PROPERTY_NOT_ADD  => self::helpMe()->config()->getValue(self::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)
            ],
            self::KEY_SEND_CONFIRMATION_EMAIL . "_always_enabled" => [
                self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                self::PROPERTY_DISABLED => true,
                self::PROPERTY_NOT_ADD  => (!self::helpMe()->config()->getValue(self::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)),
                "setTitle"              => $this->txt(self::KEY_SEND_CONFIRMATION_EMAIL),
                "setInfo"               => self::plugin()->translate("always_enabled", self::LANG_MODULE, [
                    implode(" > ", [$this->txt("recipient"), $this->txt("recipient_create_jira_ticket"), $this->txt("jira_create_service_desk_request")])
                ])
            ],
            self::KEY_INFO_TEXTS                                  => [
                self::PROPERTY_CLASS    => TabsInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_SUBITEMS => MultilangualTabsInputGUI::generate([
                    self::KEY_INFO_TEXT => [
                        self::PROPERTY_CLASS => TextAreaInputGUI::class,
                        "setUseRte"          => true,
                        "setRteTagSet"       => "extended"
                    ]
                ], true)
            ],
            self::KEY_ROLES                                       => [
                self::PROPERTY_CLASS    => MultiSelectSearchNewInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_OPTIONS  => self::helpMe()->ilias()->roles()->getAllRoles()
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("configuration"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch (true) {
            case (strpos($key, self::KEY_RECIPIENT_TEMPLATES . "_") === 0):
                $template_name = substr($key, strlen(self::KEY_RECIPIENT_TEMPLATES) + 1);

                $template_names = $this->getValue(self::KEY_RECIPIENT_TEMPLATES);

                $template_names[$template_name] = $value;

                $key = self::KEY_RECIPIENT_TEMPLATES;
                $value = $template_names;

                self::helpMe()->config()->setValue($key, $value);
                break;

            case ($key === self::KEY_SEND_CONFIRMATION_EMAIL . "_always_enabled"):
                break;

            default:
                self::helpMe()->config()->setValue($key, $value);
                break;
        }
    }


    /**
     * @param string $template_name
     *
     * @return array
     */
    protected function getTemplateSelection(string $template_name) : array
    {
        return [
            self::KEY_RECIPIENT_TEMPLATES . "_" . $template_name => [
                self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_OPTIONS  => ["" => ""] + array_combine(array_map(function (NotificationInterface $notification) : string {
                        return $notification->getName();
                    }, self::helpMe()->notifications4plugin()->notifications()
                        ->getNotifications()), array_map(function (NotificationInterface $notification) : string {
                        return $notification->getTitle();
                    }, self::helpMe()->notifications4plugin()->notifications()
                        ->getNotifications())),
                "setTitle"              => self::plugin()->translate("template_selection", NotificationsCtrl::LANG_MODULE)
            ]
        ];
    }
}
