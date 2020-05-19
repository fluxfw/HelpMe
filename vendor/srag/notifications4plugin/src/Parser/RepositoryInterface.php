<?php

namespace srag\Notifications4Plugin\HelpMe\Parser;

use srag\Notifications4Plugin\HelpMe\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationInterface;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\HelpMe\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface
{

    /**
     * @param Parser $parser
     */
    public function addParser(Parser $parser) : void;


    /**
     * @internal
     */
    public function dropTables() : void;


    /**
     * @return FactoryInterface
     */
    public function factory() : FactoryInterface;


    /**
     * @return Parser[]
     */
    public function getPossibleParsers() : array;


    /**
     * @param string $parser_class
     *
     * @return Parser
     *
     * @throws Notifications4PluginException
     */
    public function getParserByClass(string $parser_class) : Parser;


    /**
     * @param NotificationInterface $notification
     *
     * @return Parser
     *
     * @throws Notifications4PluginException
     */
    public function getParserForNotification(NotificationInterface $notification) : Parser;


    /**
     * @internal
     */
    public function installTables() : void;


    /**
     * @param Parser                $parser
     * @param NotificationInterface $notification
     * @param array                 $placeholders
     * @param string|null           $language
     *
     * @return string
     *
     * @throws Notifications4PluginException
     */
    public function parseSubject(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null) : string;


    /**
     * @param Parser                $parser
     * @param NotificationInterface $notification
     * @param array                 $placeholders
     * @param string|null           $language
     *
     * @return string
     *
     * @throws Notifications4PluginException
     */
    public function parseText(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null) : string;
}
