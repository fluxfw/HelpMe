<?php

namespace srag\Plugins\HelpMe\Config;

use ilHelpMePlugin;
use srag\ActiveRecordConfig\HelpMe\Config\AbstractFactory;
use srag\ActiveRecordConfig\HelpMe\Config\AbstractRepository;
use srag\ActiveRecordConfig\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository extends AbstractRepository
{

    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
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
    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     *
     * @return Factory
     */
    public function factory() : AbstractFactory
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return "ui_uihk_" . ilHelpMePlugin::PLUGIN_ID . "_config_n";
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        return [
            ConfigFormGUI::KEY_EMAIL_FIELD                            => Config::TYPE_STRING,
            ConfigFormGUI::KEY_INFO                                   => Config::TYPE_STRING,
            ConfigFormGUI::KEY_INFO_TEXTS                             => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_JIRA_ACCESS_TOKEN                      => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_AUTHORIZATION                     => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_CONSUMER_KEY                      => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_DOMAIN                            => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST       => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_JIRA_PASSWORD                          => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_PRIORITY_FIELD                    => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_PRIVATE_KEY                       => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_CREATE_AS_CUSTOMER   => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_CREATE_NEW_CUSTOMERS => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_ID                   => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_LINK_TYPE            => Config::TYPE_STRING,
            ConfigFormGUI::KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID      => Config::TYPE_INTEGER,
            ConfigFormGUI::KEY_JIRA_USERNAME                          => Config::TYPE_STRING,
            ConfigFormGUI::KEY_NAME_FIELD                             => Config::TYPE_STRING,
            ConfigFormGUI::KEY_PAGE_REFERENCE                         => Config::TYPE_BOOLEAN,
            ConfigFormGUI::KEY_PRIORITIES                             => [Config::TYPE_JSON, []],
            ConfigFormGUI::KEY_RECIPIENT                              => Config::TYPE_STRING,
            ConfigFormGUI::KEY_RECIPIENT_TEMPLATES                    => [Config::TYPE_JSON, [], true],
            ConfigFormGUI::KEY_ROLES                                  => [Config::TYPE_JSON, []],
            ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL                => [Config::TYPE_BOOLEAN, true],
            ConfigFormGUI::KEY_SEND_EMAIL_ADDRESS                     => Config::TYPE_STRING,
            ConfigFormGUI::KEY_USAGE_HIDDEN                           => [Config::TYPE_JSON, [], true]
        ];
    }
}
