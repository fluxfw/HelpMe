<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ActiveRecord;
use arConnector;
use ilDateTime;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Notification\Language\NotificationLanguage;
use srag\Notifications4Plugin\HelpMe\Notification\Language\Repository as NotificationLanguageRepository;
use srag\Notifications4Plugin\HelpMe\Notification\Language\RepositoryInterface as NotificationLanguageRepositoryInterface;
use srag\Notifications4Plugin\HelpMe\Notification\Repository as NotificationRepository;
use srag\Notifications4Plugin\HelpMe\Notification\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\HelpMe\Parser\twigParser;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;
use Throwable;

/**
 * Class AbstractNotification
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
abstract class AbstractNotification extends ActiveRecord implements Notification
{

    use DICTrait;
    use Notifications4PluginTrait;
    /**
     * @var string
     *
     * @abstract
     */
    const LANGUAGE_CLASS_NAME = "";


    /**
     * @inheritdoc
     */
    protected static function notification() : NotificationRepositoryInterface
    {
        return NotificationRepository::getInstance(static::class, static::LANGUAGE_CLASS_NAME);
    }


    /**
     * @inheritdoc
     */
    protected static function notificationLanguage() : NotificationLanguageRepositoryInterface
    {
        return NotificationLanguageRepository::getInstance(static::LANGUAGE_CLASS_NAME);
    }


    /**
     * @return string
     */
    public function getConnectorContainerName() : string
    {
        return static::TABLE_NAME;
    }


    /**
     * @return string
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return static::TABLE_NAME;
    }


    /**
     *
     */
    public static function updateDB_()/*: void*/
    {
        try {
            self::updateDB();
        } catch (Throwable $ex) {
            // Fix Call to a member function getName() on null (Because not use ILIAS primary key)
        }

        if (self::dic()->database()->sequenceExists(static::TABLE_NAME)) {
            self::dic()->database()->dropSequence(static::TABLE_NAME);
        }

        self::dic()->database()->createAutoIncrement(static::TABLE_NAME, "id");
    }


    /**
     *
     */
    public static function dropDB_()/*: void*/
    {
        self::dic()->database()->dropTable(static::TABLE_NAME, false);

        self::dic()->database()->dropAutoIncrementTable(static::TABLE_NAME);
    }


    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     */
    protected $id = 0;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       1024
     * @con_is_notnull   true
     * @con_is_unique    true
     */
    protected $name = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       1024
     * @con_is_notnull   true
     */
    protected $title = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       4000
     * @con_is_notnull   true
     */
    protected $description = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       2
     * @con_is_notnull   true
     */
    protected $default_language = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $parser = twigParser::class;
    /**
     * @var ilDateTime
     *
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     */
    protected $created_at;
    /**
     * @var ilDateTime
     *
     * @con_has_field    true
     * @con_fieldtype    timestamp
     * @con_is_notnull   true
     */
    protected $updated_at;
    /**
     * @var NotificationLanguage[]
     */
    protected $languages = [];


    /**
     * AbstractNotification constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        //parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritdoc
     */
    public function getId() : int
    {
        return $this->id;
    }


    /**
     * @inheritdoc
     */
    public function setId(int $id)/*: void*/
    {
        $this->id = $id;
    }


    /**
     * @inheritdoc
     */
    public function getName() : string
    {
        return $this->name;
    }


    /**
     * @inheritdoc
     */
    public function setName(string $name)/*: void*/
    {
        $this->name = $name;
    }


    /**
     * @inheritdoc
     */
    public function getTitle() : string
    {
        return $this->title;
    }


    /**
     * @inheritdoc
     */
    public function setTitle(string $title)/*: void*/
    {
        $this->title = $title;
    }


    /**
     * @inheritdoc
     */
    public function getDescription() : string
    {
        return $this->description;
    }


    /**
     * @inheritdoc
     */
    public function setDescription(string $description)/*: void*/
    {
        $this->description = $description;
    }


    /**
     * @inheritdoc
     */
    public function getDefaultLanguage() : string
    {
        return $this->default_language;
    }


    /**
     * @inheritdoc
     */
    public function setDefaultLanguage(string $default_language)/*: void*/
    {
        $this->default_language = $default_language;
    }


    /**
     * @inheritdoc
     */
    public function getParser() : string
    {
        return $this->parser;
    }


    /**
     * @inheritdoc
     */
    public function setParser(string $parser)/*: void*/
    {
        $this->parser = $parser;
    }


    /**
     * @inheritdoc
     */
    public function getCreatedAt() : ilDateTime
    {
        return $this->created_at;
    }


    /**
     * @inheritdoc
     */
    public function setCreatedAt(ilDateTime $created_at)/*: void*/
    {
        $this->created_at = $created_at;
    }


    /**
     * @inheritdoc
     */
    public function getUpdatedAt() : ilDateTime
    {
        return $this->updated_at;
    }


    /**
     * @inheritdoc
     */
    public function setUpdatedAt(ilDateTime $updated_at)/*: void*/
    {
        $this->updated_at = $updated_at;
    }


    /**
     * @inheritdoc
     */
    public function getLanguages() : array
    {
        return $this->languages;
    }


    /**
     * @inheritdoc
     */
    public function setLanguages(array $languages)/*: void*/
    {
        $this->languages = $languages;
    }


    /**
     * @inheritdoc
     */
    public function addLanguage(NotificationLanguage $language)/*: void*/
    {
        $this->languages[$language->getLanguage()] = $language;
    }


    /**
     * @param string $language
     * @param bool   $allow_create_new
     *
     * @return NotificationLanguage
     */
    protected function getNotificationLanguage(string $language = "", bool $allow_create_new = false) : NotificationLanguage
    {
        if (empty($language) || (!isset($this->languages[$language]) && !$allow_create_new)) {
            $language = $this->default_language;
        }

        if (isset($this->languages[$language])) {
            $l = $this->languages[$language];
        } else {
            $l = $this->languages[$language] = self::notificationLanguage()->getLanguageForNotification($this->id, $language);
        }

        return $l;
    }


    /**
     * @inheritdoc
     */
    public function getSubject(string $language = "") : string
    {
        $language = $this->getNotificationLanguage($language);

        return $language->getSubject();
    }


    /**
     * @inheritdoc
     */
    public function setSubject(string $subject, string $language)/*: void*/
    {
        $language = $this->getNotificationLanguage($language, true);

        $language->setSubject($subject);
    }


    /**
     * @inheritdoc
     */
    public function getText(string $language = "") : string
    {
        $language = $this->getNotificationLanguage($language);

        return $language->getText();
    }


    /**
     * @inheritdoc
     */
    public function setText(string $text, string $language)/*: void*/
    {
        $language = $this->getNotificationLanguage($language, true);

        $language->setText($text);
    }
}
