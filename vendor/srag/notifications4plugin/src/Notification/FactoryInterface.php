<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use srag\Notifications4Plugin\HelpMe\Notification\Table\TableBuilder;
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
     *
     * @return TableBuilder
     */
    public function newTableBuilderInstance(NotificationsCtrl $parent) : TableBuilder;


    /**
     * @param NotificationCtrl      $parent
     * @param NotificationInterface $notification
     *
     * @return NotificationFormGUI
     */
    public function newFormInstance(NotificationCtrl $parent, NotificationInterface $notification) : NotificationFormGUI;
}
