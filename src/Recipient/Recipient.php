<?php

namespace srag\Plugins\HelpMe\Recipient;

use ilHelpMePlugin;
use ilMimeMail;
use PHPMailer\PHPMailer\Exception as phpmailerException;
use srag\ActiveRecordConfig\HelpMe\Exception\ActiveRecordConfigException;
use srag\DIC\HelpMe\DICTrait;
use srag\DIC\HelpMe\Exception\DICException;
use srag\HelpMe\Exception\HelpMeException;
use srag\Notifications4Plugins\Exception\Notifications4PluginsException;
use srag\Plugins\HelpMe\Config\Config;
use srag\Plugins\HelpMe\Support\Support;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;
use srag\Plugins\Notifications4Plugins\Utils\Notifications4PluginsTrait;

/**
 * Class Recipient
 *
 * @package srag\Plugins\HelpMe\Recipient
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class Recipient {

	use DICTrait;
	use HelpMeTrait;
	use Notifications4PluginsTrait;
	const SEND_EMAIL = "send_email";
	const CREATE_JIRA_TICKET = "create_jira_ticket";
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	/**
	 * @var Support
	 */
	protected $support;


	/**
	 * @param string  $recipient
	 * @param Support $support
	 *
	 * @return Recipient|null
	 *
	 * @throws ActiveRecordConfigException
	 */
	public static function getRecipient(string $recipient, Support $support)/*: ?Recipient*/ {
		switch ($recipient) {
			case self::SEND_EMAIL:
				return new RecipientSendMail($support);

			case self::CREATE_JIRA_TICKET:
				return new RecipientCreateJiraTicket($support);

			default:
				return null;
		}
	}


	/**
	 * Recipient constructor
	 *
	 * @param Support $support
	 */
	protected function __construct(Support $support) {
		$this->support = $support;
	}


	/**
	 * Send confirmation email
	 *
	 * @throws ActiveRecordConfigException
	 * @throws DICException
	 * @throws HelpMeException
	 * @throws Notifications4PluginsException
	 * @throws phpmailerException
	 */
	protected function sendConfirmationMail()/*: void*/ {
		if (Config::getField(Config::KEY_SEND_CONFIRMATION_EMAIL)) {
			$mailer = new ilMimeMail();

			$mailer->From(self::dic()->mailMimeSenderFactory()->system());

			$mailer->To($this->support->getEmail());

			$mailer->Subject($this->getSubject(Config::KEY_SEND_CONFIRMATION_EMAIL));

			$mailer->Body($this->getBody(Config::KEY_SEND_CONFIRMATION_EMAIL));

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
	 * @throws ActiveRecordConfigException
	 * @throws Notifications4PluginsException
	 */
	public function getSubject(string $template_name): string {
		$notification = self::notification()->getNotificationByName(Config::getField(Config::KEY_RECIPIENT_TEMPLATES)[$template_name]);

		return self::parser()->parseSubject(self::parser()->getParserForNotification($notification), $notification, [
			"support" => $this->support
		]);
	}


	/**
	 * @param string $template_name
	 *
	 * @return string
	 * @throws ActiveRecordConfigException
	 * @throws DICException
	 * @throws Notifications4PluginsException
	 */
	public function getBody(string $template_name): string {
		$notification = self::notification()->getNotificationByName(Config::getField(Config::KEY_RECIPIENT_TEMPLATES)[$template_name]);

		$fields_ = [
			"project" => $this->support->getProject()->getProjectName() . " (" . $this->support->getProject()->getProjectKey() . ")",
			"title" => $this->support->getTitle(),
			"name" => $this->support->getName(),
			"login" => $this->support->getLogin(),
			"email" => $this->support->getEmail(),
			"phone" => $this->support->getPhone(),
			"priority" => $this->support->getPriority(),
			"description" => $this->support->getDescription(),
			"reproduce_steps" => $this->support->getReproduceSteps(),
			"system_infos" => $this->support->getSystemInfos(),
			"datetime" => $this->support->getFormatedTime()
		];

		$fields = [];
		foreach ($fields_ as $key => $value) {
			$fields[self::plugin()->translate($key, SupportGUI::LANG_MODULE_SUPPORT)] = $value;
		}

		return self::parser()->parseText(self::parser()->getParserForNotification($notification), $notification, [
			"support" => $this->support,
			"fields" => $fields
		]);
	}


	/**
	 * Send support to recipient
	 *
	 * @throws ActiveRecordConfigException
	 * @throws DICException
	 * @throws HelpMeException
	 * @throws Notifications4PluginsException
	 * @throws phpmailerException
	 */
	public abstract function sendSupportToRecipient()/*: void*/
	;
}
