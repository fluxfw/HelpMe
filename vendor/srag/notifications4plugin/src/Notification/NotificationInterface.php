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

    /**
     * @return string
     */
    public static function getTableName() : string;


    /**
     * @return int
     */
    public function getId() : int;


    /**
     * @param int $id
     */
    public function setId(int $id)/*: void*/ ;


    /**
     * @return string
     */
    public function getName() : string;


    /**
     * @param string $name
     */
    public function setName(string $name)/*: void*/ ;


    /**
     * @return string
     */
    public function getTitle() : string;


    /**
     * @param string $title
     */
    public function setTitle(string $title)/*: void*/ ;


    /**
     * @return string
     */
    public function getDescription() : string;


    /**
     * @param string $description
     */
    public function setDescription(string $description)/*: void*/ ;


    /**
     * @return string
     */
    public function getParser() : string;


    /**
     * @param string $parser
     */
    public function setParser(string $parser)/*: void*/ ;


    /**
     * @return ilDateTime
     */
    public function getCreatedAt() : ilDateTime;


    /**
     * @param ilDateTime $created_at
     */
    public function setCreatedAt(ilDateTime $created_at)/*: void*/ ;


    /**
     * @return ilDateTime
     */
    public function getUpdatedAt() : ilDateTime;


    /**
     * @param ilDateTime $updated_at
     */
    public function setUpdatedAt(ilDateTime $updated_at)/*: void*/ ;


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
    public function getSubject(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string;


    /**
     * @param array $subjects
     */
    public function setSubjects(array $subjects)/* : void*/ ;


    /**
     * @param string $subject
     * @param string $lang_key
     */
    public function setSubject(string $subject, string $lang_key)/*: void*/ ;


    /**
     * @return array
     */
    public function getTexts() : array;


    /**
     * @param string|null $lang_key
     * @param bool        $use_default_if_not_set
     *
     * @return string
     */
    public function getText(/*?*/ string $lang_key = null, bool $use_default_if_not_set = true) : string;


    /**
     * @param array $texts
     */
    public function setTexts(array $texts)/* : void*/ ;


    /**
     * @param string $text
     * @param string $lang_key
     */
    public function setText(string $text, string $lang_key)/*: void*/ ;
}
