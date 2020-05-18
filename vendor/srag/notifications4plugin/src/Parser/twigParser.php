<?php

namespace srag\Notifications4Plugin\HelpMe\Parser;

use srag\Notifications4Plugin\HelpMe\Notification\NotificationsCtrl;
use Twig_Environment;
use Twig_Error;
use Twig_Loader_String;

/**
 * Class twigParser
 *
 * @package srag\Notifications4Plugin\HelpMe\Parser
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
class twigParser extends AbstractParser
{

    const NAME = "twig";
    const DOC_LINK = "https://twig.symfony.com/doc/1.x/templates.html";


    /**
     * twigParser constructor
     *
     * @param array $options
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    public function getOptionsFields() : array
    {
        return [
            "autoescape" => self::dic()
                ->ui()
                ->factory()
                ->input()
                ->field()
                ->checkbox(self::notifications4plugin()->getPlugin()->translate("parser_option_autoescape", NotificationsCtrl::LANG_MODULE))
                ->withByline(nl2br(implode("\n", [
                    self::notifications4plugin()->getPlugin()->translate("parser_option_autoescape_info_1", NotificationsCtrl::LANG_MODULE, ["|raw"]),
                    self::notifications4plugin()->getPlugin()->translate("parser_option_autoescape_info_2", NotificationsCtrl::LANG_MODULE, ["|e"]),
                    "<b>" . self::notifications4plugin()->getPlugin()->translate("parser_option_autoescape_info_3", NotificationsCtrl::LANG_MODULE) . "</b>"
                ]), false))
        ];
    }


    /**
     * @inheritDoc
     *
     * @throws Twig_Error
     */
    public function parse(string $text, array $placeholders = [], array $options = []) : string
    {
        $loader = new Twig_Loader_String();

        $twig = new Twig_Environment($loader, [
            "autoescape" => boolval($options["autoescape"])
        ]);

        return $twig->render($text, $placeholders);
    }
}
