<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ilConfirmationGUI;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class NotificationCtrl
 *
 * @package           srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Notifications4Plugin\HelpMe\Notification\NotificationCtrl: srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl
 */
class NotificationCtrl
{

    use DICTrait;
    use Notifications4PluginTrait;
    const CMD_ADD_NOTIFICATION = "addNotification";
    const CMD_BACK = "back";
    const CMD_CREATE_NOTIFICATION = "createNotification";
    const CMD_DELETE_NOTIFICATION = "deleteNotification";
    const CMD_DELETE_NOTIFICATION_CONFIRM = "deleteNotificationConfirm";
    const CMD_DUPLICATE_NOTIFICATION = "duplicateNotification";
    const CMD_EDIT_NOTIFICATION = "editNotification";
    const CMD_UPDATE_NOTIFICATION = "updateNotification";
    const GET_PARAM_NOTIFICATION_ID = "notification_id";
    /**
     * @var NotificationsCtrl
     */
    protected $parent;
    /**
     * @var Notification
     */
    protected $notification;


    /**
     * NotificationCtrl constructor
     *
     * @param NotificationsCtrl $parent
     */
    public function __construct(NotificationsCtrl $parent)
    {
        $this->parent = $parent;
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->notification = self::notifications4plugin()->notifications()->getNotificationById(intval(filter_input(INPUT_GET, self::GET_PARAM_NOTIFICATION_ID)));
        if ($this->notification === null) {
            $this->notification = self::notifications4plugin()->notifications()->factory()->newInstance();
        }

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_NOTIFICATION_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_NOTIFICATION:
                    case self::CMD_BACK:
                    case self::CMD_CREATE_NOTIFICATION:
                    case self::CMD_DELETE_NOTIFICATION:
                    case self::CMD_DELETE_NOTIFICATION_CONFIRM:
                    case self::CMD_DUPLICATE_NOTIFICATION:
                    case self::CMD_EDIT_NOTIFICATION:
                    case self::CMD_UPDATE_NOTIFICATION:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {

    }


    /**
     *
     */
    protected function back()/*: void*/
    {
        self::dic()->ctrl()->redirect($this->parent, NotificationsCtrl::CMD_LIST_NOTIFICATIONS);
    }


    /**
     *
     */
    protected function addNotification()/*: void*/
    {
        $form = self::notifications4plugin()->notifications()->factory()->newFormInstance($this, $this->notification);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function createNotification()/*: void*/
    {
        $form = self::notifications4plugin()->notifications()->factory()->newFormInstance($this, $this->notification);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        self::dic()->ctrl()->setParameter($this, self::GET_PARAM_NOTIFICATION_ID, $this->notification->getId());

        ilUtil::sendSuccess(self::notifications4plugin()->getPlugin()->translate("added_notification", NotificationsCtrl::LANG_MODULE, [$this->notification->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_NOTIFICATION);
    }


    /**
     *
     */
    protected function editNotification()/*: void*/
    {
        $form = self::notifications4plugin()->notifications()->factory()->newFormInstance($this, $this->notification);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateNotification()/*: void*/
    {
        $form = self::notifications4plugin()->notifications()->factory()->newFormInstance($this, $this->notification);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::notifications4plugin()->getPlugin()->translate("saved_notification", NotificationsCtrl::LANG_MODULE, [$this->notification->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_NOTIFICATION);
    }


    /**
     *
     */
    protected function duplicateNotification()/*: void*/
    {
        $cloned_notification = self::notifications4plugin()->notifications()->duplicateNotification($this->notification);

        self::notifications4plugin()->notifications()->storeNotification($cloned_notification);

        ilUtil::sendSuccess(self::notifications4plugin()->getPlugin()
            ->translate("duplicated_notification", NotificationsCtrl::LANG_MODULE, [$cloned_notification->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function deleteNotificationConfirm()/*: void*/
    {
        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::notifications4plugin()->getPlugin()
            ->translate("delete_notification_confirm", NotificationsCtrl::LANG_MODULE, [$this->notification->getTitle()]));

        $confirmation->addItem(self::GET_PARAM_NOTIFICATION_ID, $this->notification->getId(), $this->notification->getTitle());

        $confirmation->setConfirm(self::notifications4plugin()->getPlugin()->translate("delete", NotificationsCtrl::LANG_MODULE), self::CMD_DELETE_NOTIFICATION);
        $confirmation->setCancel(self::notifications4plugin()->getPlugin()->translate("cancel", NotificationsCtrl::LANG_MODULE), self::CMD_BACK);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function deleteNotification()/*: void*/
    {
        self::notifications4plugin()->notifications()->deleteNotification($this->notification);

        ilUtil::sendSuccess(self::notifications4plugin()->getPlugin()->translate("deleted_notification", NotificationsCtrl::LANG_MODULE, [$this->notification->getTitle()]), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     * @return NotificationsCtrl
     */
    public function getParent() : NotificationsCtrl
    {
        return $this->parent;
    }
}
