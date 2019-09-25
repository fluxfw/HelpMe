<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use srag\DIC\HelpMe\Plugin\PluginInterface;

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
     * @param Notification $notification
     */
    public function deleteNotification(Notification $notification)/*: void*/ ;


    /**
     * @param Notification    $notification
     * @param PluginInterface $plugin
     *
     * @return Notification
     */
    public function duplicateNotification(Notification $notification, PluginInterface $plugin) : Notification;


    /**
     * @return FactoryInterface
     */
    public function factory() : FactoryInterface;


    /**
     * @param Notification[] $notifications
     *
     * @return array
     */
    public function getArrayForSelection(array $notifications) : array;


    /**
     * @param int $id
     *
     * @return Notification|null
     */
    public function getNotificationById(int $id)/*: ?Notification*/ ;


    /**
     * @param string $name
     *
     * @return Notification|null
     */
    public function getNotificationByName(string $name)/*: ?Notification*/ ;


    /**
     * @param string|null $sort_by
     * @param string|null $sort_by_direction
     * @param int|null    $limit_start
     * @param int|null    $limit_end
     *
     * @return Notification[]
     */
    public function getNotifications(string $sort_by = null, string $sort_by_direction = null, int $limit_start = null, int $limit_end = null) : array;


    /**
     * @return int
     */
    public function getNotificationsCount() : int;


    /**
     * @param string $name |null
     *
     * @return Notification|null
     *
     * @deprecated
     */
    public function migrateFromOldGlobalPlugin(string $name = null)/*: ?Notification*/ ;


    /**
     * @param Notification $notification
     */
    public function storeInstance(Notification $notification)/*: void*/ ;
}
