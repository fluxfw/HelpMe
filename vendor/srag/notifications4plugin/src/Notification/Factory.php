<?php

namespace srag\Notifications4Plugin\HelpMe\Notification;

use ilDateTime;
use srag\DIC\HelpMe\DICTrait;
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
     * @var FactoryInterface[]
     */
    protected static $instances = [];


    /**
     * @param string $notification_class
     *
     * @return FactoryInterface
     */
    public static function getInstance(string $notification_class) : FactoryInterface
    {
        if (!isset(self::$instances[$notification_class])) {
            self::$instances[$notification_class] = new self($notification_class);
        }

        return self::$instances[$notification_class];
    }


    /**
     * @var string|Notification
     */
    protected $notification_class;


    /**
     * Factory constructor
     *
     * @param string $notification_class
     */
    private function __construct(string $notification_class)
    {
        $this->notification_class = $notification_class;
    }


    /**
     * @inheritdoc
     */
    public function fromDB(stdClass $data) : Notification
    {
        $language = $this->newInstance();

        $language->setId($data->id);
        $language->setName($data->name);
        $language->setTitle($data->title);
        $language->setDescription($data->description);
        $language->setDefaultLanguage($data->default_language);
        $language->setParser($data->parser);
        $language->setCreatedAt(new ilDateTime($data->created_at, IL_CAL_DATETIME));
        $language->setUpdatedAt(new ilDateTime($data->updated_at, IL_CAL_DATETIME));

        return $language;
    }


    /**
     * @inheritdoc
     */
    public function newInstance() : Notification
    {
        $notification = new $this->notification_class();

        return $notification;
    }
}
