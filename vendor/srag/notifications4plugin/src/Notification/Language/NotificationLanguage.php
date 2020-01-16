<?php

namespace srag\Notifications4Plugin\HelpMe\Notification\Language;

use ActiveRecord;
use arConnector;
use ilDateTime;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class NotificationLanguage
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification\Language
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @deprecated
 */
class NotificationLanguage extends ActiveRecord
{

    use DICTrait;
    use Notifications4PluginTrait;
    /**
     * @var string
     *
     * @deprecated
     */
    const TABLE_NAME_SUFFIX = "not_lan";


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function getTableName() : string
    {
        return self::notifications4plugin()->getTableNamePrefix() . "_" . self::TABLE_NAME_SUFFIX;
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public function getConnectorContainerName() : string
    {
        return self::getTableName();
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::getTableName();
    }


    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     *
     * @deprecated
     */
    protected $id = 0;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     *
     * @deprecated
     */
    protected $notification_id;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       2
     * @con_is_notnull   true
     *
     * @deprecated
     */
    protected $language = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    clob
     * @con_length       256
     * @con_is_notnull   true
     *
     * @deprecated
     */
    protected $subject = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    clob
     * @con_length       4000
     * @con_is_notnull   true
     *
     * @deprecated
     */
    protected $text = "";
    /**
     * @var ilDateTime
     *
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     *
     * @deprecated
     */
    protected $created_at;
    /**
     * @var ilDateTime
     *
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     *
     * @deprecated
     */
    protected $updated_at;


    /**
     * NotificationLanguage constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     *
     * @deprecated
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        //parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public function getSubject() : string
    {
        return $this->subject;
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public function getText() : string
    {
        return $this->text;
    }
}
