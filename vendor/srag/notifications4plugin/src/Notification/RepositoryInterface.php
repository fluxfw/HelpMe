<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

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
    public function deleteNotification(NotificationInterface $notification)/* : void*/;


    /**
     * @internal
     */
    public function dropTables()/* : void*/;


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
    public function getNotificationById(int $id)/* : ?NotificationInterface*/;


    /**
     * @param string $name
     *
     * @return NotificationInterface|null
     */
    public function getNotificationByName(string $name)/* : ?NotificationInterface*/;


    /**
     * @param string|null $sort_by
     * @param string|null $sort_by_direction
     * @param int|null    $limit_start
     * @param int|null    $limit_end
     *
     * @return NotificationInterface[]
     */
    public function getNotifications(string $sort_by = null, string $sort_by_direction = null, int $limit_start = null, int $limit_end = null) : array;


    /**
     * @return int
     */
    public function getNotificationsCount() : int;


    /**
     * @internal
     */
    public function installTables()/* : void*/;


    /**
     * @param string $name |null
     *
     * @return NotificationInterface|null
     *
     * @deprecated
     */
    public function migrateFromOldGlobalPlugin(string $name = null)/* : ?Notification*/;


    /**
     * @param NotificationInterface $notification
     */
    public function storeNotification(NotificationInterface $notification)/* : void*/;
}
