<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use stdClass;

/**
 * Interface FactoryInterface
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface FactoryInterface
{

    /**
     * @param stdClass $data
     *
     * @return NotificationInterface
     */
    public function fromDB(stdClass $data) : NotificationInterface;


    /**
     * @return NotificationInterface
     */
    public function newInstance() : NotificationInterface;


    /**
     * @param NotificationsCtrl $parent
     * @param string            $parent_cmd
     *
     * @return NotificationsTableGUI
     */
    public function newTableInstance(NotificationsCtrl $parent, string $parent_cmd = NotificationsCtrl::CMD_LIST_NOTIFICATIONS) : NotificationsTableGUI;


    /**
     * @param NotificationCtrl      $parent
     * @param NotificationInterface $notification
     *
     * @return NotificationFormGUI
     */
    public function newFormInstance(NotificationCtrl $parent, NotificationInterface $notification) : NotificationFormGUI;
}
