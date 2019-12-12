<?php

namespace srag\Plugins\HelpMe\Support;

use ilHelpMePlugin;
use ilLink;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\DICTrait;
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Support\Recipient\Repository as RecipientRepository;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\HelpMe\Support
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use HelpMeTrait;
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    const GET_PARAM_REF_ID = "ref_id";
    const GET_PARAM_TARGET = "target";
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {
        $this->recipient()->dropTables();
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * Get browser infos
     *
     * @return string "Browser Version / System Version"
     */
    public function getBrowserInfos() : string
    {
        $browser = new Browser();
        $os = new Os();

        $infos = $browser->getName() . (($browser->getVersion() !== Browser::UNKNOWN) ? " " . $browser->getVersion() : "") . " / " . $os->getName()
            . (($os->getVersion() !== Os::UNKNOWN) ? " " . $os->getVersion() : "");

        return $infos;
    }


    /**
     * @param string $recipient_url_key
     *
     * @return string
     */
    public function getLink(string $recipient_url_key = "") : string
    {
        return ILIAS_HTTP_PATH . "/goto.php?target=uihk_" . ilHelpMePlugin::PLUGIN_ID . (!empty($recipient_url_key) ? "_" . $recipient_url_key : "");
    }


    /**
     * @return int|null
     */
    public function getRefId()/*: ?int*/
    {
        if (!Config::getField(Config::KEY_PAGE_REFERENCE)) {
            return null;
        }

        $obj_ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);

        if ($obj_ref_id === null) {
            $param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);

            $obj_ref_id = explode("_", $param_target)[1];
        }

        $obj_ref_id = intval($obj_ref_id);

        if ($obj_ref_id > 0) {
            return $obj_ref_id;
        } else {
            return null;
        }
    }


    /**
     * @return string
     */
    public function getRefLink() : string
    {
        $ref_id = $this->getRefId();

        if ($ref_id === null) {
            return "";
        }

        return ilLink::_getStaticLink($ref_id);
    }


    /**
     * @return JiraCurl
     *
     * @throws ActiveRecordConfigException
     */
    public function initJiraCurl() : JiraCurl
    {
        $jira_curl = new JiraCurl();

        $jira_curl->setJiraDomain(Config::getField(Config::KEY_JIRA_DOMAIN));

        $jira_curl->setJiraAuthorization(Config::getField(Config::KEY_JIRA_AUTHORIZATION));

        $jira_curl->setJiraUsername(Config::getField(Config::KEY_JIRA_USERNAME));
        $jira_curl->setJiraPassword(Config::getField(Config::KEY_JIRA_PASSWORD));

        $jira_curl->setJiraConsumerKey(Config::getField(Config::KEY_JIRA_CONSUMER_KEY));
        $jira_curl->setJiraPrivateKey(Config::getField(Config::KEY_JIRA_PRIVATE_KEY));
        $jira_curl->setJiraAccessToken(Config::getField(Config::KEY_JIRA_ACCESS_TOKEN));

        return $jira_curl;
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        $this->recipient()->installTables();
    }


    /**
     * @return RecipientRepository
     */
    public function recipient() : RecipientRepository
    {
        return RecipientRepository::getInstance();
    }
}
