<?php

namespace srag\Notifications4Plugin\HelpMe\Notification\Table;

use srag\DataTableUI\HelpMe\Component\Table;
use srag\DataTableUI\HelpMe\Implementation\Utils\AbstractTableBuilder;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationCtrl;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class TableBuilder
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification\Table
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TableBuilder extends AbstractTableBuilder
{

    use Notifications4PluginTrait;

    /**
     * @inheritDoc
     *
     * @param NotificationsCtrl $parent
     */
    public function __construct(NotificationsCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function buildTable() : Table
    {
        $table = self::dataTableUI()->table("notifications4plugin_" . self::notifications4plugin()->getPlugin()->getPluginObject()->getId(),
            self::dic()->ctrl()->getLinkTarget($this->parent, NotificationsCtrl::CMD_LIST_NOTIFICATIONS, "", false, false),
            "", [
                self::dataTableUI()->column()->column("title",
                    self::notifications4plugin()->getPlugin()->translate("title", NotificationsCtrl::LANG_MODULE))->withDefaultSort(true),
                self::dataTableUI()->column()->column("description",
                    self::notifications4plugin()->getPlugin()->translate("description", NotificationsCtrl::LANG_MODULE)),
                self::dataTableUI()->column()->column("name",
                    self::notifications4plugin()->getPlugin()->translate("name", NotificationsCtrl::LANG_MODULE)),
                self::dataTableUI()->column()->column("actions",
                    self::notifications4plugin()->getPlugin()->translate("actions", NotificationsCtrl::LANG_MODULE))->withFormatter(self::dataTableUI()
                    ->column()
                    ->formatter()
                    ->actions()
                    ->actionsDropdown())
            ], new DataFetcher())->withPlugin(self::notifications4plugin()->getPlugin());

        return $table;
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        self::dic()->toolbar()->addComponent(self::dic()->ui()->factory()->button()->standard(self::notifications4plugin()->getPlugin()->translate("add_notification", NotificationsCtrl::LANG_MODULE),
            self::dic()->ctrl()->getLinkTargetByClass(NotificationCtrl::class, NotificationCtrl::CMD_ADD_NOTIFICATION, "", false, false)));

        return parent::render();
    }
}
