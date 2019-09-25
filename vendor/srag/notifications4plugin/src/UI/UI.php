<?php

namespace srag\Notifications4Plugin\HelpMe\UI;

use ilConfirmationGUI;
use ilSelectInputGUI;
use ilUtil;
use srag\CustomInputGUIs\HelpMe\PropertyFormGUI\PropertyFormGUI;
use srag\DIC\HelpMe\DICTrait;
use srag\DIC\HelpMe\Plugin\PluginInterface;
use srag\Notifications4Plugin\HelpMe\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\HelpMe\Notification\Notification;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class UI
 *
 * @package srag\Notifications4Plugin\HelpMe\UI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class UI implements UIInterface
{

    use DICTrait;
    use Notifications4PluginTrait;
    /**
     * @var UIInterface
     */
    protected static $instance = null;


    /**
     * @return UIInterface
     */
    public static function getInstance() : UIInterface
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @var CtrlInterface
     */
    protected $ctrl_class;
    /**
     * @var PluginInterface|null
     */
    protected $plugin = null;


    /**
     * UI constructor
     */
    private function __construct()
    {

    }


    /**
     * @inheritdoc
     */
    public function getPlugin() : PluginInterface
    {
        return $this->plugin;
    }


    /**
     * @inheritdoc
     */
    public function withPlugin(PluginInterface $plugin) : self
    {
        $this->plugin = $plugin;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function withCtrlClass(CtrlInterface $ctrl_class) : UIInterface
    {
        $this->ctrl_class = $ctrl_class;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function notificationDeleteConfirmation(Notification $notification) : ilConfirmationGUI
    {
        $confirmation = new ilConfirmationGUI();

        self::dic()->ctrl()->setParameter($this->ctrl_class, CtrlInterface::GET_PARAM, $notification->getId());
        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this->ctrl_class));
        self::dic()->ctrl()->setParameter($this->ctrl_class, CtrlInterface::GET_PARAM, null);

        $confirmation->setHeaderText($this->getPlugin()
            ->translate("delete_notification_confirm", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN, [$notification->getTitle()]));

        $confirmation->addItem(CtrlInterface::GET_PARAM, $notification->getId(), $notification->getTitle());

        $confirmation->setConfirm($this->getPlugin()
            ->translate("delete", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN), CtrlInterface::CMD_DELETE_NOTIFICATION);
        $confirmation->setCancel($this->getPlugin()
            ->translate("cancel", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN), CtrlInterface::CMD_LIST_NOTIFICATIONS);

        return $confirmation;
    }


    /**
     * @inheritdoc
     */
    public function notificationForm(Notification $notification) : NotificationFormGUI
    {
        ilUtil::sendInfo(self::output()->getHTML([
            $this->getPlugin()->translate("placeholder_types_info", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN, [CtrlInterface::NAME]),
            "<br><br>",
            self::dic()->ui()->factory()->listing()->descriptive($this->ctrl_class->getPlaceholderTypes())
        ]));

        $form = new NotificationFormGUI($this->getPlugin(), $this->ctrl_class, $notification);

        return $form;
    }


    /**
     * @inheritdoc
     */
    public function notificationTable(string $parent_cmd, callable $getNotifications, callable $getNotificationsCount) : NotificationsTableGUI
    {
        $table = new NotificationsTableGUI($this->getPlugin(), $this->ctrl_class, $parent_cmd, $getNotifications, $getNotificationsCount);

        return $table;
    }


    /**
     * @inheritdoc
     */
    public function templateSelection(array $notifications, string $post_key, bool $required = true) : array
    {
        return [
            $post_key => [
                PropertyFormGUI::PROPERTY_CLASS    => ilSelectInputGUI::class,
                PropertyFormGUI::PROPERTY_REQUIRED => $required,
                PropertyFormGUI::PROPERTY_OPTIONS  => ["" => ""] + $notifications,
                "setTitle"                         => $this->getPlugin()
                    ->translate("template_selection", CtrlInterface::LANG_MODULE_NOTIFICATIONS4PLUGIN, [CtrlInterface::NAME]),
                "setInfo"                          => ""
            ]
        ];
    }
}
