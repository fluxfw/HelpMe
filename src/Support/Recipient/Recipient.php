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
    protected function sendConfirmationMail() : void
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

        $fields = [];
        foreach ($this->support->getFormattedFieldValues() as $key => $value) {
            if (is_array($value)) {
                $fields[] = self::helpMe()->support()->factory()->newFieldInstance($key, $value[0], $value[1], $value[2]);
            } else {
                $fields[] = self::helpMe()->support()->factory()->newFieldInstance($key, $key, self::plugin()->translate($key, SupportGUI::LANG_MODULE), $value);
            }
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
    public abstract function sendSupportToRecipient() : void;
}
