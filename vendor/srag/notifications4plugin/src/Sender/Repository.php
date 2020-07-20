<?php

namespace srag\Notifications4Plugin\HelpMe\Sender;

use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationInterface;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\HelpMe\Sender
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository implements RepositoryInterface
{

    use DICTrait;
    use Notifications4PluginTrait;

    /**
     * @var RepositoryInterface|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @return RepositoryInterface
     */
    public static function getInstance() : RepositoryInterface
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @inheritDoc
     */
    public function dropTables() : void
    {

    }


    /**
     * @inheritDoc
     */
    public function factory() : FactoryInterface
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function installTables() : void
    {

    }


    /**
     * @inheritDoc
     */
    public function send(Sender $sender, NotificationInterface $notification, array $placeholders = [], ?string $language = null) : void
    {
        $parser = self::notifications4plugin()->parser()->getParserForNotification($notification);

        $sender->setSubject(self::notifications4plugin()->parser()->parseSubject($parser, $notification, $placeholders, $language));

        $sender->setMessage(self::notifications4plugin()->parser()->parseText($parser, $notification, $placeholders, $language));

        $sender->send();
    }
}
