<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ActiveRecord;
use arConnector;
use ilDateTime;
use srag\CustomInputGUIs\HelpMe\TabsInputGUI\MultilangualTabsInputGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Parser\twigParser;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class Notification
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class Notification extends ActiveRecord implements NotificationInterface
{

    use DICTrait;
    use Notifications4PluginTrait;
    const TABLE_NAME_SUFFIX = "not";


    /**
     * @inheritDoc
     */
    public static function getTableName() : string
    {
        return self::notifications4plugin()->getTableNamePrefix() . "_" . self::TABLE_NAME_SUFFIX;
    }


    /**
     * @return string
     */
    public function getConnectorContainerName()
    {
        return static::getTableName();
    }


    /**
     * @return string
     *
     * @deprecated
     */
    public static function returnDbTableName()
    {
        return static::getTableName();
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
     * @con_is_notnull   true
     */
    protected $parser = twigParser::class;
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $subject = [];
    /**
     * @var array
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $text = [];
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
     * Notification constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        //parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     */
    public function getSubjects() : array
    {
        return $this->subject;
    }


    /**
     * @inheritDoc
     */
    public function getSubject(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string
    {
        return strval(MultilangualTabsInputGUI::getValueForLang($this->subject, $lang_key, "subject", $use_default_if_not_set));
    }


    /**
     * @inheritDoc
     */
    public function setSubjects(array $subjects)/*:void*/
    {
        $this->subject = $subjects;
    }


    /**
     * @inheritDoc
     */
    public function setSubject(string $subject, string $lang_key)/*: void*/
    {
        MultilangualTabsInputGUI::setValueForLang($this->subject, $subject, $lang_key, "subject");
    }


    /**
     * @inheritDoc
     */
    public function getTexts() : array
    {
        return $this->text;
    }


    /**
     * @inheritDoc
     */
    public function getText(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string
    {
        return strval(MultilangualTabsInputGUI::getValueForLang($this->text, $lang_key, "text", $use_default_if_not_set));
    }


    /**
     * @inheritDoc
     */
    public function setTexts(array $texts)/*:void*/
    {
        $this->text = $texts;
    }


    /**
     * @inheritDoc
     */
    public function setText(string $text, string $lang_key)/*: void*/
    {
        MultilangualTabsInputGUI::setValueForLang($this->text, $text, $lang_key, "text");
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "subject":
            case "text":
                return json_encode($field_value);

            default:
                return null;
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "subject":
            case "text":
                return json_decode($field_value, true);

            default:
                return null;
        }
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
}
