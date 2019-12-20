<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ilDateTime;
use ilUtil;
use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Ctrl\CtrlInterface;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;
use stdClass;

/**
 * Class Factory
 *
 * @package srag\Notifications4Plugin\HelpMe\Notification
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory implements FactoryInterface
{

    use DICTrait;
    use Notifications4PluginTrait;
    /**
     * @var FactoryInterface|null
     */
    protected static $instance = null;


    /**
     * @return FactoryInterface
     */
    public static function getInstance() : FactoryInterface
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @inheritdoc
     */
    public function fromDB(stdClass $data) : NotificationInterface
    {
        $notification = $this->newInstance();

        $notification->setId($data->id);
        $notification->setName($data->name);
        $notification->setTitle($data->title);
        $notification->setDescription($data->description);
        $notification->setParser($data->parser);
        $notification->setSubjects(json_decode($data->subject, true) ?? []);
        $notification->setTexts(json_decode($data->text, true) ?? []);
        $notification->setCreatedAt(new ilDateTime($data->created_at, IL_CAL_DATETIME));
        $notification->setUpdatedAt(new ilDateTime($data->updated_at, IL_CAL_DATETIME));

        if (isset($data->default_language)) {
            $notification->default_language = $data->default_language;
        }

        return $notification;
    }


    /**
     * @inheritdoc
     */
    public function newInstance() : NotificationInterface
    {
        $notification = new Notification();

        return $notification;
    }


    /**
     * @inheritdoc
     */
    public function newTableInstance(NotificationsCtrl $parent, string $parent_cmd = NotificationsCtrl::CMD_LIST_NOTIFICATIONS) : NotificationsTableGUI
    {
        $table = new NotificationsTableGUI($parent, $parent_cmd);

        return $table;
    }


    /**
     * @inheritdoc
     */
    public function newFormInstance(NotificationCtrl $parent, NotificationInterface $notification) : NotificationFormGUI
    {
        ilUtil::sendInfo(self::output()->getHTML([
            self::notifications4plugin()->getPlugin()->translate("placeholder_types_info", NotificationsCtrl::LANG_MODULE),
            "<br><br>",
            self::dic()->ui()->factory()->listing()->descriptive(self::notifications4plugin()->getPlaceholderTypes())
        ]));

        $form = new NotificationFormGUI($parent, $notification);

        return $form;
    }
}
