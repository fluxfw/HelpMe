<?php

namespace srag\Notifications4Plugin\HelpMe\Parser;

use srag\DIC\HelpMe\DICTrait;
use srag\Notifications4Plugin\HelpMe\Utils\Notifications4PluginTrait;

/**
 * Class AbstractParser
 *
 * @package srag\Notifications4Plugin\HelpMe\Parser
 */
abstract class AbstractParser implements Parser
{

    use DICTrait;
    use Notifications4PluginTrait;

    /**
     * AbstractParser constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getClass() : string
    {
        return static::class;
    }


    /**
     * @inheritDoc
     */
    public function getDocLink() : string
    {
        return static::DOC_LINK;
    }


    /**
     * @inheritDoc
     */
    public function getName() : string
    {
        return static::NAME;
    }


    /**
     * @param string $html
     *
     * @return string
     */
    protected function fixLineBreaks(string $html) : string
    {
        return str_ireplace(["&lt;br&gt;", "&lt;br/&gt;", "&lt;br /&gt;"], ["<br>", "<br/>", "<br />"], $html);
    }
}
