<?php

namespace srag\Plugins\HelpMe\Config;

use ilHelpMePlugin;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfig;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Config
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig
{

    use HelpMeTrait;
    const TABLE_NAME = "ui_uihk_srsu_config_n";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const KEY_INFO = "info";
    const KEY_JIRA_ACCESS_TOKEN = "jira_access_token";
    const KEY_JIRA_AUTHORIZATION = "jira_authorization";
    const KEY_JIRA_CONSUMER_KEY = "jira_consumer_key";
    const KEY_JIRA_CREATE_SERVICE_DESK_REQUEST = "jira_create_service_desk_request";
    const KEY_JIRA_DOMAIN = "jira_domain";
    const KEY_JIRA_PASSWORD = "jira_password";
    const KEY_JIRA_PRIVATE_KEY = "jira_private_key";
    const KEY_JIRA_SERVICE_DESK_ID = "jira_service_desk_id";
    const KEY_JIRA_SERVICE_DESK_LINK_TYPE = "jira_service_desk_link_type";
    const KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID = "jira_service_desk_request_type_id";
    const KEY_JIRA_USERNAME = "jira_username";
    const KEY_PAGE_REFERENCE = "page_reference";
    const KEY_PRIORITIES = "priorities";
    const KEY_RECIPIENT = "recipient";
    const KEY_RECIPIENT_TEMPLATES = "recipient_templates";
    const KEY_ROLES = "roles";
    const KEY_SEND_CONFIRMATION_EMAIL = "send_confirmation_email";
    const KEY_SEND_EMAIL_ADDRESS = "send_email_address";
    const KEY_USAGE_HIDDEN = "usage_hidden";
    /**
     * @var array
     */
    protected static $fields
        = [
            self::KEY_INFO                              => self::TYPE_STRING,
            self::KEY_JIRA_ACCESS_TOKEN                 => self::TYPE_STRING,
            self::KEY_JIRA_AUTHORIZATION                => self::TYPE_STRING,
            self::KEY_JIRA_CONSUMER_KEY                 => self::TYPE_STRING,
            self::KEY_JIRA_DOMAIN                       => self::TYPE_STRING,
            self::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST  => self::TYPE_BOOLEAN,
            self::KEY_JIRA_PASSWORD                     => self::TYPE_STRING,
            self::KEY_JIRA_PRIVATE_KEY                  => self::TYPE_STRING,
            self::KEY_JIRA_SERVICE_DESK_ID              => self::TYPE_INTEGER,
            self::KEY_JIRA_SERVICE_DESK_LINK_TYPE       => self::TYPE_STRING,
            self::KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID => self::TYPE_INTEGER,
            self::KEY_JIRA_USERNAME                     => self::TYPE_STRING,
            self::KEY_PAGE_REFERENCE                    => self::TYPE_BOOLEAN,
            self::KEY_PRIORITIES                        => [self::TYPE_JSON, []],
            self::KEY_RECIPIENT                         => self::TYPE_STRING,
            self::KEY_RECIPIENT_TEMPLATES               => [self::TYPE_JSON, [], true],
            self::KEY_ROLES                             => [self::TYPE_JSON, []],
            self::KEY_SEND_CONFIRMATION_EMAIL           => [self::TYPE_BOOLEAN, true],
            self::KEY_SEND_EMAIL_ADDRESS                => self::TYPE_STRING,
            self::KEY_USAGE_HIDDEN                      => [self::TYPE_JSON, [], true]
        ];
}
