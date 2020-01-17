<?php

namespace srag\Plugins\HelpMe\Support\Recipient;

use ilHelpMePlugin;
use ilMimeMail;
use PHPMailer\PHPMailer\Exception as phpmailerException;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\DICTrait;
use srag\DIC\HelpMe\Exception\DICException;
use srag\Notifications4Plugin\HelpMe\Exception\Notifications4PluginException;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Exception\HelpMeException;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportField;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Recipient
 *
 * @package srag\Plugins\HelpMe\Support\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class Recipient
{

    use DICTrait;
    use HelpMeTrait;
    const SEND_EMAIL = "send_email";
    const CREATE_JIRA_TICKET = "create_jira_ticket";
    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var Support
     */
    protected $support;


    /**
     * Recipient constructor
     *
     * @param Support $support
     */
    protected function __construct(Support $support)
    {
        $this->support = $support;
    }


    /**
     * Send confirmation email
     *
     * @throws ActiveRecordConfigException
     * @throws DICException
     * @throws HelpMeException
     * @throws Notifications4PluginException
     * @throws phpmailerException
     */
    protected function sendConfirmationMail()/*: void*/
    {
        if (self::helpMe()->config()->getValue(ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL)) {
            $mailer = new ilMimeMail();

            $mailer->From(self::dic()->mailMimeSenderFactory()->system());

            $mailer->To($this->support->getEmail());

            $mailer->Subject($this->getSubject(ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL));

            $mailer->Body($this->getBody(ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL));

            foreach ($this->support->getScreenshots() as $screenshot) {
                $mailer->Attach($screenshot->getPath(), $screenshot->getMimeType(), "attachment", $screenshot->getName());
            }

            $sent = $mailer->Send();

            if (!$sent) {
                throw new HelpMeException("Mailer not returns true");
            }
        }
    }


    /**
     * @param string $template_name
     *
     * @return string
     *
     * @throws ActiveRecordConfigException
     * @throws Notifications4PluginException
     */
    public function getSubject(string $template_name) : string
    {
        $notification = self::helpMe()->notifications4plugin()->notifications()->getNotificationByName(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_RECIPIENT_TEMPLATES)[$template_name]);

        return self::helpMe()->notifications4plugin()->parser()->parseSubject(self::helpMe()->notifications4plugin()->parser()->getParserForNotification($notification), $notification, [
            "support" => $this->support
        ]);
    }


    /**
     * @param string $template_name
     *
     * @return string
     *
     * @throws ActiveRecordConfigException
     * @throws DICException
     * @throws Notifications4PluginException
     */
    public function getBody(string $template_name) : string
    {
        $notification = self::helpMe()->notifications4plugin()->notifications()->getNotificationByName(self::helpMe()->config()->getValue(ConfigFormGUI::KEY_RECIPIENT_TEMPLATES)[$template_name]);

        $fields_ = (!empty($this->support->getPageReference()) ? [
                "page_reference" => $this->support->getPageReference()
            ] : []) + [
                "project"         => $this->support->getProject()->getProjectName() . " (" . $this->support->getProject()->getProjectKey() . ")",
                "issue_type"      => $this->support->getIssueType(),
                "title"           => $this->support->getTitle(),
                "name"            => $this->support->getName(),
                "login"           => $this->support->getLogin(),
                "email"           => $this->support->getEmail(),
                "phone"           => $this->support->getPhone(),
                "priority"        => $this->support->getPriority(),
                "description"     => $this->support->getDescription(),
                "reproduce_steps" => $this->support->getReproduceSteps(),
                "system_infos"    => $this->support->getSystemInfos(),
                "datetime"        => $this->support->getFormatedTime()
            ];

        $fields = [];
        foreach ($fields_ as $key => $value) {
            $fields[] = new SupportField($key, self::plugin()->translate($key, SupportGUI::LANG_MODULE), $value);
        }

        return self::helpMe()->notifications4plugin()->parser()->parseText(self::helpMe()->notifications4plugin()->parser()->getParserForNotification($notification), $notification, [
            "support" => $this->support,
            "fields"  => $fields
        ]);
    }


    /**
     * Send support to recipient
     *
     * @throws ActiveRecordConfigException
     * @throws DICException
     * @throws HelpMeException
     * @throws Notifications4PluginException
     * @throws phpmailerException
     */
    public abstract function sendSupportToRecipient()/*: void*/ ;
}
