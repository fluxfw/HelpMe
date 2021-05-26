<?php

namespace srag\Notifications4Plugin\HelpMe\Parser;

use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Exception\Notifications4PluginException;
use srag\Notifications4Plugin\HelpMe\Notification\NotificationInterface;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class Repository
 *
 * @package srag\Notifications4Plugin\HelpMe\Parser
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
     * @var Parser[]
     */
    protected $parsers = [];


    /**
     * Repository constructor
     */
    private function __construct()
    {
        $this->addParser($this->factory()->twig());
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
    public function addParser(Parser $parser) : void
    {
        $this->parsers[$parser->getClass()] = $parser;
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
    public function getParserByClass(string $parser_class) : Parser
    {
        if (isset($this->getPossibleParsers()[$parser_class])) {
            return $this->getPossibleParsers()[$parser_class];
        } else {
            throw new Notifications4PluginException("Invalid parser class $parser_class");
        }
    }


    /**
     * @inheritDoc
     */
    public function getParserForNotification(NotificationInterface $notification) : Parser
    {
        return $this->getParserByClass($notification->getParser());
    }


    /**
     * @inheritDoc
     */
    public function getPossibleParsers() : array
    {
        return $this->parsers;
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
    public function parseSubject(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null) : string
    {
        return $parser->parse($notification->getSubject($language), $placeholders, $notification->getParserOptions());
    }


    /**
     * @inheritDoc
     */
    public function parseText(Parser $parser, NotificationInterface $notification, array $placeholders = [], ?string $language = null) : string
    {
        return $parser->parse($notification->getText($language), $placeholders, $notification->getParserOptions());
    }
}
