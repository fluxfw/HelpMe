<?php

namespace srag\Plugins\HelpMe\Config;

use ilEMailInputGUI;
use ilHelpMePlugin;
use ilMultiSelectInputGUI;
use ilPasswordInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilTextAreaInputGUI;
use ilTextInputGUI;
use srag\ActiveRecordConfig\HelpMe\ActiveRecordConfigFormGUI;
use srag\JiraCurl\HelpMe\JiraCurl;
use srag\Plugins\HelpMe\Recipient\Recipient;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\HelpMe\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	use HelpMeTrait;
	const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
	const CONFIG_CLASS_NAME = Config::class;


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			Config::KEY_RECIPIENT => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_SUBITEMS => [
					Recipient::SEND_EMAIL => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							Config::KEY_SEND_EMAIL_ADDRESS => [
								self::PROPERTY_CLASS => ilEMailInputGUI::class,
								self::PROPERTY_REQUIRED => true
							]
						]
					],
					Recipient::CREATE_JIRA_TICKET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							Config::KEY_JIRA_DOMAIN => [
								self::PROPERTY_CLASS => ilTextInputGUI::class,
								self::PROPERTY_REQUIRED => true
							],
							Config::KEY_JIRA_ISSUE_TYPE => [
								self::PROPERTY_CLASS => ilTextInputGUI::class,
								self::PROPERTY_REQUIRED => true
							],
							Config::KEY_JIRA_AUTHORIZATION => [
								self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
								self::PROPERTY_REQUIRED => true,
								self::PROPERTY_SUBITEMS => [
									JiraCurl::AUTHORIZATION_USERNAMEPASSWORD => [
										self::PROPERTY_CLASS => ilRadioOption::class,
										self::PROPERTY_SUBITEMS => [
											Config::KEY_JIRA_USERNAME => [
												self::PROPERTY_CLASS => ilTextInputGUI::class,
												self::PROPERTY_REQUIRED => true
											],
											Config::KEY_JIRA_PASSWORD => [
												self::PROPERTY_CLASS => ilPasswordInputGUI::class,
												self::PROPERTY_REQUIRED => true,
												"setRetype" => false
											]
										]
									],
									JiraCurl::AUTHORIZATION_OAUTH => [
										self::PROPERTY_CLASS => ilRadioOption::class,
										self::PROPERTY_SUBITEMS => [
											Config::KEY_JIRA_CONSUMER_KEY => [
												self::PROPERTY_CLASS => ilTextInputGUI::class,
												self::PROPERTY_REQUIRED => true
											],
											Config::KEY_JIRA_PRIVATE_KEY => [
												self::PROPERTY_CLASS => ilTextAreaInputGUI::class,
												self::PROPERTY_REQUIRED => true
											],
											Config::KEY_JIRA_ACCESS_TOKEN => [
												self::PROPERTY_CLASS => ilTextInputGUI::class,
												self::PROPERTY_REQUIRED => true
											],
										]
									]
								]
							],
						]
					]
				]
			],
			Config::KEY_PRIORITIES => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_MULTI => true
			],
			Config::KEY_INFO => [
				self::PROPERTY_CLASS => ilTextAreaInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				"setUseRte" => true,
				"setRteTagSet" => "extended"
			],
			Config::KEY_ROLES => [
				self::PROPERTY_CLASS => ilMultiSelectInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				self::PROPERTY_OPTIONS => self::ilias()->roles()->getAllRoles(),
				"enableSelectAll" => true
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			case Config::KEY_ROLES:
				array_shift($value);

				$value = array_map(function (string $role_id): int {
					return intval($role_id);
				}, $value);
				break;

			default:
				break;
		}

		parent::storeValue($key, $value);
	}
}
