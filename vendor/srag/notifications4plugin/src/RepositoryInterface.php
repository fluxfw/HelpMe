<?php

namespace srag\Notifications4Plugin\HelpMe;

use srag\DIC\HelpMe\Plugin\Pluginable;
use srag\Notifications4Plugin\HelpMe\Notification\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\HelpMe\Parser\RepositoryInterface as ParserRepositoryInterface;
use srag\Notifications4Plugin\HelpMe\Sender\RepositoryInterface as SenderRepositoryInterface;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\HelpMe
 */
interface RepositoryInterface extends Pluginable
{

    /**
     *
     */
    public function dropTables() : void;


    /**
     * @return array
     */
    public function getPlaceholderTypes() : array;


    /**
     * @return string
     */
    public function getTableNamePrefix() : string;


    /**
     *
     */
    public function installLanguages() : void;


    /**
     *
     */
    public function installTables() : void;


    /**
     * @return NotificationRepositoryInterface
     */
    public function notifications() : NotificationRepositoryInterface;


    /**
     * @return ParserRepositoryInterface
     */
    public function parser() : ParserRepositoryInterface;


    /**
     * @return SenderRepositoryInterface
     */
    public function sender() : SenderRepositoryInterface;


    /**
     * @param array $placeholder_types
     *
     * @return self
     */
    public function withPlaceholderTypes(array $placeholder_types) : self;


    /**
     * @param string $table_name_prefix
     *
     * @return self
     */
    public function withTableNamePrefix(string $table_name_prefix) : self;
}
