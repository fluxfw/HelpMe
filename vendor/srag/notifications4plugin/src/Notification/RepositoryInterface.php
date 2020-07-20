<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use srag\DataTableUI\HelpMe\Component\Settings\Settings;

/**
 * Interface RepositoryInterface
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface RepositoryInterface
{

    /**
     * @param NotificationInterface $notification
     */
    public function deleteNotification(NotificationInterface $notification) : void;


    /**
     * @internal
     */
    public function dropTables() : void;


    /**
     * @param NotificationInterface $notification
     *
     * @return NotificationInterface
     */
    public function duplicateNotification(NotificationInterface $notification) : NotificationInterface;


    /**
     * @return FactoryInterface
     */
    public function factory() : FactoryInterface;


    /**
     * @param int $id
     *
     * @return NotificationInterface|null
     */
    public function getNotificationById(int $id) : ?NotificationInterface;


    /**
     * @param string $name
     *
     * @return NotificationInterface|null
     */
    public function getNotificationByName(string $name) : ?NotificationInterface;


    /**
     * @param Settings|null $settings
     *
     * @return NotificationInterface[]
     */
    public function getNotifications(?Settings $settings = null) : array;


    /**
     * @return int
     */
    public function getNotificationsCount() : int;


    /**
     * @internal
     */
    public function installTables() : void;


    /**
     * @param string $name |null
     *
     * @return NotificationInterface|null
     *
     * @deprecated
     */
    public function migrateFromOldGlobalPlugin(string $name = null) : ?NotificationInterface;


    /**
     * @param NotificationInterface $notification
     */
    public function storeNotification(NotificationInterface $notification) : void;
}
