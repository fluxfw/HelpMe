<?php

namespace srag\Plugins\HelpMe\Support\Recipient;

use ilCurlConnectionException;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\Exception\DICException;
use srag\JiraCurl\HelpMe\Exception\JiraCurlException;
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Notifications4Plugin\HelpMe\Exception\Notifications4PluginException;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Support\Support;

/**
 * Class RecipientCreateJiraTicket
 *
 * @package srag\Plugins\HelpMe\Support\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecipientCreateJiraTicket extends Recipient
{

    /**
     * @var JiraCurl
     */
    protected $jira_curl;
    /**
     * @var string|null
     */
    protected $service_desk_customer = null;
    /**
     * @var string
     */
    protected $service_desk_ticket_key = "";
    /**
     * @var string
     */
    protected $ticket_key = "";
    /**
     * @var string
     */
    protected $ticket_title = "";


    /**
     * RecipientCreateJiraTicket constructor
     *
     * @param Support $support
     *
     * @throws ActiveRecordConfigException
     */
    public function __construct(Support $support)
    {
        parent::__construct($support);

        $this->jira_curl = self::helpMe()->support()->initJiraCurl();
    }


    /**
     * @inheritDoc
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    public function sendSupportToRecipient() : void
    {
        if (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)) {
            if (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_SERVICE_DESK_CREATE_AS_CUSTOMER)) {
                if (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_SERVICE_DESK_CREATE_NEW_CUSTOMERS)) {
                    $this->service_desk_customer = $this->jira_curl->ensureServiceDeskCustomer($this->support->getEmail(), $this->support->getName());
                } else {
                    $this->service_desk_customer = $this->support->getEmail();
                }
            }

            $this->createServiceDeskRequest();

            $this->addScreenshotsToServiceDeskRequest();
        }

        $this->createJiraTicket();

        $this->addScreenshots();

        if (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_CREATE_SERVICE_DESK_REQUEST)) {
            $this->linkServiceDeskAndProjectTicket();
        } else {
            $this->sendConfirmationMail();
        }

        if (self::helpMe()->tickets()->isEnabled()) {
            $ticket = self::helpMe()->tickets()->factory()->fromSupport($this->support, $this->ticket_key, $this->ticket_title);

            self::helpMe()->tickets()->storeTicket($ticket);
        }
    }


    /**
     * Add screenshots to Jira ticket
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    protected function addScreenshots() : void
    {
        $this->jira_curl->addAttachmentsToIssue($this->ticket_key, $this->support->getScreenshots());
    }


    /**
     * Add screenshots to service desk request
     *
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    protected function addScreenshotsToServiceDeskRequest() : void
    {
        $this->jira_curl->addAttachmentsToServiceDeskRequest(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_SERVICE_DESK_ID), $this->service_desk_ticket_key,
            $this->support->getScreenshots());
    }


    /**
     * Create Jira ticket
     *
     * @throws ActiveRecordConfigException
     * @throws DICException
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     * @throws Notifications4PluginException
     */
    protected function createJiraTicket() : void
    {
        $this->ticket_title = $this->fixLineBreaks($this->getSubject(self::CREATE_JIRA_TICKET));

        $this->ticket_key = $this->jira_curl->createJiraIssueTicket($this->support->getProject()
            ->getProjectKey(), $this->support->getIssueType(), $this->ticket_title, $this->fixLineBreaks($this->getBody(self::CREATE_JIRA_TICKET)), $this->support->getPriority(),
            $this->support->getFixVersion());
    }


    /**
     * Create service desk request
     *
     * @throws ActiveRecordConfigException
     * @throws DICException
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     * @throws Notifications4PluginException
     */
    protected function createServiceDeskRequest() : void
    {
        $this->service_desk_ticket_key = $this->jira_curl->createServiceDeskRequest(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_SERVICE_DESK_ID),
            self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_SERVICE_DESK_REQUEST_TYPE_ID),
            $this->getSubject(self::CREATE_JIRA_TICKET), $this->fixLineBreaks($this->getBody(self::CREATE_JIRA_TICKET)), $this->service_desk_customer);
    }


    /**
     * @param string $html
     *
     * @return string
     */
    protected function fixLineBreaks(string $html) : string
    {
        return str_ireplace(["<br>", "<br/>", "<br />"], ["", "", ""], $html);
    }


    /**
     * Link service desk and project ticket
     *
     * @throws ActiveRecordConfigException
     * @throws DICException
     * @throws ilCurlConnectionException
     * @throws JiraCurlException
     */
    protected function linkServiceDeskAndProjectTicket() : void
    {
        $this->jira_curl->linkTickets($this->service_desk_ticket_key, $this->ticket_key, self::helpMe()->config()->getValue(ConfigFormGUI::KEY_JIRA_SERVICE_DESK_LINK_TYPE));
    }
}
