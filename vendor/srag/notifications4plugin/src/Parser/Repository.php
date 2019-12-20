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
     * @var array
     */
    protected $parsers
        = [
            twigParser::class => twigParser::NAME
        ];


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @inheritdoc
     */
    public function addParser(Parser $parser)/*:void*/
    {
        $parser_class = get_class($parser);

        $this->parsers[$parser_class] = $parser_class::NAME;
    }


    /**
     * @inheritDoc
     */
    public function dropTables()/*:void*/
    {

    }


    /**
     * @inheritdoc
     */
    public function factory() : FactoryInterface
    {
        return Factory::getInstance();
    }


    /**
     * @inheritdoc
     */
    public function getPossibleParsers() : array
    {
        return $this->parsers;
    }


    /**
     * @inheritdoc
     */
    public function getParserByClass(string $parser_class) : Parser
    {
        if (isset($this->getPossibleParsers()[$parser_class])) {
            return new $parser_class();
        } else {
            throw new Notifications4PluginException("Invalid parser class $parser_class");
        }
    }


    /**
     * @inheritdoc
     */
    public function getParserForNotification(NotificationInterface $notification) : Parser
    {
        return $this->getParserByClass($notification->getParser());
    }


    /**
     * @inheritDoc
     */
    public function installTables()/*:void*/
    {

    }


    /**
     * @inheritdoc
     */
    public function parseSubject(Parser $parser, NotificationInterface $notification, array $placeholders = [], /*?*/ string $language = null) : string
    {
        return $parser->parse($notification->getSubject($language), $placeholders);
    }


    /**
     * @inheritdoc
     */
    public function parseText(Parser $parser, NotificationInterface $notification, array $placeholders = [], /*?*/ string $language = null) : string
    {
        return $parser->parse($notification->getText($language), $placeholders);
    }
}
