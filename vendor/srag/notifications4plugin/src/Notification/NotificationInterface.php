<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ilDateTime;

/**
 * Interface NotificationInterface
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface NotificationInterface
{

    const DEFAULT_PARSER_OPTIONS
        = [
            "autoescape" => false
        ];


    /**
     * @return string
     */
    public static function getTableName() : string;


    /**
     * @return ilDateTime
     */
    public function getCreatedAt() : ilDateTime;


    /**
     * @return string
     */
    public function getDescription() : string;


    /**
     * @return int
     */
    public function getId() : int;


    /**
     * @return string
     */
    public function getName() : string;


    /**
     * @return string
     */
    public function getParser() : string;


    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParserOption(string $key);


    /**
     * @return array
     */
    public function getParserOptions() : array;


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getSubject(?string $lang_key = null, bool $use_default_if_not_set = true) : string;


    /**
     * @return array
     */
    public function getSubjects() : array;


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getText(?string $lang_key = null, bool $use_default_if_not_set = true) : string;


    /**
     * @return array
     */
    public function getTexts() : array;


    /**
     * @return string
     */
    public function getTitle() : string;


    /**
     * @return ilDateTime
     */
    public function getUpdatedAt() : ilDateTime;


    /**
     * @param ilDateTime $created_at
     */
    public function setCreatedAt(ilDateTime $created_at) : void;


    /**
     * @param string $description
     */
    public function setDescription(string $description) : void;


    /**
     * @param int $id
     */
    public function setId(int $id) : void;


    /**
     * @param string $name
     */
    public function setName(string $name) : void;


    /**
     * @param string $parser
     */
    public function setParser(string $parser) : void;


    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setParserOption(string $key, $value) : void;


    /**
     * @param array $parser_options
     */
    public function setParserOptions(array $parser_options = self::DEFAULT_PARSER_OPTIONS) : void;


    /**
     * @param string $subject
     * @param string $lang_key
     */
    public function setSubject(string $subject, string $lang_key) : void;


    /**
     * @param array $subjects
     */
    public function setSubjects(array $subjects) : void;


    /**
     * @param string $text
     * @param string $lang_key
     */
    public function setText(string $text, string $lang_key) : void;


    /**
     * @param array $texts
     */
    public function setTexts(array $texts) : void;


    /**
     * @param string $title
     */
    public function setTitle(string $title) : void;


    /**
     * @param ilDateTime $updated_at
     */
    public function setUpdatedAt(ilDateTime $updated_at) : void;
}
