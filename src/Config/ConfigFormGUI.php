<?php

namespace srag\Plugins\HelpMe\Config;

use ilCheckboxInputGUI;
use ilEMailInputGUI;
use ilHelpMeConfigGUI;
use ilHelpMePlugin;
use ilMultiSelectInputGUI;
use ilNumberInputGUI;
use ilPasswordInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationInterface;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl;
use srag\Plugins\HelpMe\Support\Recipient\Recipient;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

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
    const LANG_MODULE = ilHelpMeConfigGUI::LANG_MODULE;


    /**
     * ConfigFormGUI constructor
     *
     * @param ilHelpMeConfigGUI $parent
     */
    public function __construct(ilHelpMeConfigGUI $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch (true) {
            case (strpos($key, Config::KEY_RECIPIENT_TEMPLATES . "_") === 0):
                $template_name = substr($key, strlen(Config::KEY_RECIPIENT_TEMPLATES) + 1);

                return Config::getField(Config::KEY_RECIPIENT_TEMPLATES)[$template_name];

            case ($key === Config::KEY_SEND_CONFIRMATION_EMAIL . "_always_enabled"):
                return true;

            default:
                return Config::getField($key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(ilHelpMeConfigGUI::CMD_UPDATE_CONFIGURE, $this->txt("save"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            Config::KEY_RECIPIENT                                   => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_SUBITEMS => [
                    Recipient::SEND_EMAIL         => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                                Config::KEY_SEND_EMAIL_ADDRESS => [
                                    self::PROPERTY_CLASS    => ilEMailInputGUI::class,
                                    self::PROPERTY_REQUIRED => true
                                ]
                            ] + $this->getTemplateSelection(Recipient::SEND_EMAIL)
                    ],
                    Recipient::CREATE_JIRA_TICKET => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                                Config::KEY_JIRA_DOMAIN        => [
                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                    self::PROPERTY_REQUIRED => true
                                ],
                                Config::KEY_JIRA_AUTHORIZATION => [
                                    self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                                    self::PROPERTY_REQUIRED => true,
                                    self::PROPERTY_SUBITEMS => [
                                        JiraCurl::AUTHORIZATION_USERNAMEPASSWORD => [
                                            self::PROPERTY_CLASS    => ilRadioOption::class,
                                            self::PROPERTY_SUBITEMS => [
                                                Config::KEY_JIRA_USERNAME => [
                                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ],
                                                Config::KEY_JIRA_PASSWORD => [
                                                    self::PROPERTY_CLASS    => ilPasswordInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true,
                                                    "setRetype"             => false
                                                ]
                                            ]
                                        ],
                                        JiraCurl::AUTHORIZATION_OAUTH            => [
                                            self::PROPERTY_CLASS    => ilRadioOption::class,
                                            self::PROPERTY_SUBITEMS => [
                                                Config::KEY_JIRA_CONSUMER_KEY => [
                                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ],
                                                Config::KEY_JIRA_PRIVATE_KEY  => [
                                                    self::PROPERTY_CLASS    => ilTextAreaInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ],
                                                Config::KEY_JIRA_ACCESS_TOKEN => [
                                                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                                    self::PROPERTY_REQUIRED => true
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ] + $this->getTemplateSelection(Recipient::CREATE_JIRA_TICKET) + [
                                Config::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST => [
                                    self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                                    self::PROPERTY_SUBITEMS => [
                                        Config::KEY_JIRA_SERVICE_DESK_ID                 => [
                                            self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                            self::PROPERTY_REQUIRED => true
                                        ],
                                        Config::KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID    => [
                                            self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                            self::PROPERTY_REQUIRED => true
                                        ],
                                        Config::KEY_JIRA_SERVICE_DESK_CREATE_AS_CUSTOMER => [
                                            self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                                            self::PROPERTY_SUBITEMS => [
                                                Config::KEY_JIRA_SERVICE_DESK_CREATE_NEW_CUSTOMERS => [
                                                    self::PROPERTY_CLASS => ilCheckboxInputGUI::class
                                                ]
                                            ]
                                        ],
                                        Config::KEY_JIRA_SERVICE_DESK_LINK_TYPE          => [
                                            self::PROPERTY_CLASS    => ilTextInputGUI::class,
                                            self::PROPERTY_REQUIRED => true
                                        ]
                                    ]
                                ]
                            ]
                    ]
                ]
            ],
            Config::KEY_SEND_CONFIRMATION_EMAIL                     => [
                self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                self::PROPERTY_SUBITEMS => $this->getTemplateSelection(Config::KEY_SEND_CONFIRMATION_EMAIL),
                self::PROPERTY_NOT_ADD  => Config::getField(Config::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)
            ],
            Config::KEY_SEND_CONFIRMATION_EMAIL . "_always_enabled" => [
                self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                self::PROPERTY_DISABLED => true,
                self::PROPERTY_NOT_ADD  => (!Config::getField(Config::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)),
                "setTitle"              => $this->txt(Config::KEY_SEND_CONFIRMATION_EMAIL),
                "setInfo"               => self::plugin()->translate("always_enabled", self::LANG_MODULE, [
                    implode(" > ", [$this->txt("recipient"), $this->txt("recipient_create_jira_ticket"), $this->txt("jira_create_service_desk_request")])
                ])
            ],
            Config::KEY_PRIORITIES                                  => [
                self::PROPERTY_CLASS    => ilTextInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_MULTI    => true
            ],
            Config::KEY_INFO                                        => [
                self::PROPERTY_CLASS    => ilTextAreaInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                "setUseRte"             => true,
                "setRteTagSet"          => "extended"
            ],
            Config::KEY_ROLES                                       => [
                self::PROPERTY_CLASS    => ilMultiSelectInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_OPTIONS  => self::helpMe()->ilias()->roles()->getAllRoles(),
                "enableSelectAll"       => true
            ],
            Config::KEY_PAGE_REFERENCE                              => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
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
            case (strpos($key, Config::KEY_RECIPIENT_TEMPLATES . "_") === 0):
                $template_name = substr($key, strlen(Config::KEY_RECIPIENT_TEMPLATES) + 1);

                $template_names = $this->getValue(Config::KEY_RECIPIENT_TEMPLATES);

                $template_names[$template_name] = $value;

                $key = Config::KEY_RECIPIENT_TEMPLATES;
                $value = $template_names;

                Config::setField($key, $value);
                break;

            case ($key === Config::KEY_ROLES):
                if ($value[0] === "") {
                    array_shift($value);
                }

                $value = array_map(function (string $role_id) : int {
                    return intval($role_id);
                }, $value);

                Config::setField($key, $value);
                break;

            case ($key === Config::KEY_SEND_CONFIRMATION_EMAIL . "_always_enabled"):
                break;

            default:
                Config::setField($key, $value);
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
            Config::KEY_RECIPIENT_TEMPLATES . "_" . $template_name => [
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
