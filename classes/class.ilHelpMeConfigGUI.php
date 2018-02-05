<?php

require_once "Services/Component/classes/class.ilPluginConfigGUI.php";
require_once "Services/Form/classes/class.ilPropertyFormGUI.php";
require_once "Services/Form/classes/class.ilRadioGroupInputGUI.php";
require_once "Services/Form/classes/class.ilRadioOption.php";
require_once "Services/Form/classes/class.ilTextInputGUI.php";
require_once "Services/Form/classes/class.ilEMailInputGUI.php";
require_once "Services/Form/classes/class.ilTextAreaInputGUI.php";
require_once "Services/Form/classes/class.ilMultiSelectInputGUI.php";
require_once "Services/Form/classes/class.ilPasswordInputGUI.php";
require_once "Services/Utilities/classes/class.ilUtil.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/Recipient/class.ilHelpMeRecipient.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/JiraCurl/class.ilJiraCurl.php";
require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/class.ilHelpMePlugin.php";

/**
 * HelpMe Config GUI
 */
class ilHelpMeConfigGUI extends ilPluginConfigGUI {

	const CMD_CONFIGURE = "configure";
	const CMD_UPDATE_CONFIGURE = "updateConfigure";
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilHelpMeUIHookGUI
	 */
	protected $pl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;


	function __construct() {
		global $DIC;

		$this->ctrl = $DIC->ctrl();
		$this->pl = ilHelpMePlugin::getInstance();
		$this->tpl = $DIC->ui()->mainTemplate();
	}


	/**
	 *
	 * @param string $cmd
	 */
	function performCommand($cmd) {
		$next_class = $this->ctrl->getNextClass($this);

		switch ($next_class) {
			default:
				switch ($cmd) {
					case self::CMD_CONFIGURE:
					case self::CMD_UPDATE_CONFIGURE:
						$this->$cmd();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 * @return ilPropertyFormGUI
	 */
	protected function getConfigurationForm() {
		$config = ilHelpMeConfig::getConfig();
		$configPriorities = ilHelpMeConfigPriority::getConfigPrioritiesArray();
		$allRoles = ilHelpMeConfigRole::getAllRoles();
		$configRoles = ilHelpMeConfigRole::getConfigRolesArray();

		$form = new ilPropertyFormGUI();

		$form->setFormAction($this->ctrl->getFormAction($this));

		$form->setTitle($this->txt("srsu_configuration"));

		$form->addCommandButton(self::CMD_UPDATE_CONFIGURE, $this->txt("srsu_save"));

		// Recipient
		$recipient = new ilRadioGroupInputGUI($this->txt("srsu_recipient"), "srsu_recipient");
		$recipient->setRequired(true);
		$recipient->setValue($config->getRecipient());
		$form->addItem($recipient);

		// Send email
		$recipient_email = new ilRadioOption($this->txt("srsu_send_email"), ilHelpMeRecipient::SEND_EMAIL);
		$recipient->addOption($recipient_email);

		$send_email_address = new ilEMailInputGUI($this->txt("srsu_email_address"), "srsu_send_email_address");
		$send_email_address->setRequired(true);
		$send_email_address->setValue($config->getSendEmailAddress());
		$recipient_email->addSubItem($send_email_address);

		// Create Jira ticket
		$recipient_jira = new ilRadioOption($this->txt("srsu_create_jira_ticket"), ilHelpMeRecipient::CREATE_JIRA_TICKET);
		$recipient->addOption($recipient_jira);

		$jira_domain = new ilTextInputGUI($this->txt("srsu_jira_domain"), "srsu_jira_domain");
		$jira_domain->setRequired(true);
		$jira_domain->setValue($config->getJiraDomain());
		$recipient_jira->addSubItem($jira_domain);

		$jira_project_key = new ilTextInputGUI($this->txt("srsu_jira_project_key"), "srsu_jira_project_key");
		$jira_project_key->setRequired(true);
		$jira_project_key->setValue($config->getJiraProjectKey());
		$recipient_jira->addSubItem($jira_project_key);

		$jira_issue_type = new ilTextInputGUI($this->txt("srsu_jira_issue_type"), "srsu_jira_issue_type");
		$jira_issue_type->setRequired(true);
		$jira_issue_type->setInfo("Task, Bug, ...");
		$jira_issue_type->setValue($config->getJiraIssueType());
		$recipient_jira->addSubItem($jira_issue_type);

		// Jira authorization
		$jira_authorization = new ilRadioGroupInputGUI($this->txt("srsu_jira_authorization"), "srsu_jira_authorization");
		$jira_authorization->setRequired(true);
		$jira_authorization->setValue($config->getJiraAuthorization());
		$recipient_jira->addSubItem($jira_authorization);

		// Username & Password
		$jira_authorization_userpassword = new ilRadioOption($this->txt("srsu_jira_usernamepassword"), ilJiraCurl::AUTHORIZATION_USERNAMEPASSWORD);
		$jira_authorization->addOption($jira_authorization_userpassword);

		$jira_username = new ilTextInputGUI($this->txt("srsu_jira_username"), "srsu_jira_username");
		$jira_username->setRequired(true);
		$jira_username->setValue($config->getJiraUsername());
		$jira_authorization_userpassword->addSubItem($jira_username);

		$jira_password = new ilPasswordInputGUI($this->txt("srsu_jira_password"), "srsu_jira_password");
		$jira_password->setRequired(true);
		$jira_password->setRetype(false);
		$jira_password->setValue($config->getJiraPassword());
		$jira_authorization_userpassword->addSubItem($jira_password);

		// oAuth
		$jira_oauth = new ilRadioOption($this->txt("srsu_jira_oauth"), ilJiraCurl::AUTHORIZATION_OAUTH);
		$jira_authorization->addOption($jira_oauth);

		$jira_consumer_key = new ilTextInputGUI($this->txt("srsu_jira_consumer_key"), "srsu_jira_consumer_key");
		$jira_consumer_key->setRequired(true);
		$jira_consumer_key->setValue($config->getJiraConsumerKey());
		$jira_oauth->addSubItem($jira_consumer_key);

		$jira_private_key = new ilTextAreaInputGUI($this->txt("srsu_jira_private_key"), "srsu_jira_private_key");
		$jira_private_key->setRequired(true);
		$jira_private_key->setInfo("PEM formatted RSA private key");
		$jira_private_key->setValue($config->getJiraPrivateKey());
		$jira_oauth->addSubItem($jira_private_key);

		$jira_access_token = new ilTextInputGUI($this->txt("srsu_jira_access_token"), "srsu_jira_access_token");
		$jira_access_token->setRequired(true);
		$jira_access_token->setValue($config->getJiraAccessToken());
		$jira_oauth->addSubItem($jira_access_token);

		// Priorities
		$priorities = new ilTextInputGUI($this->txt("srsu_priorities"), "srsu_priorities");
		$priorities->setMulti(true);
		$priorities->setRequired(true);
		$priorities->setValue($configPriorities);
		$form->addItem($priorities);

		// Info text
		$info = new ilTextAreaInputGUI($this->txt("srsu_info"), "srsu_info");
		$info->setRequired(true);
		$info->setUseRte(true);
		$info->setRteTagSet("extended");
		$info->setValue($config->getInfo());
		$form->addItem($info);

		// Roles
		$roles = new ilMultiSelectInputGUI($this->txt("srsu_roles"), "srsu_roles");
		$roles->setRequired(true);
		$roles->setInfo($this->txt("srsu_roles_description"));
		$roles->setOptions($allRoles);
		$roles->setValue($configRoles);
		$roles->enableSelectAll(true);
		$form->addItem($roles);

		return $form;
	}


	/**
	 * @param string $html
	 */
	protected function show($html) {
		$this->tpl->setContent($html);
	}


	/**
	 *
	 */
	protected function configure() {
		$form = $this->getConfigurationForm();

		$this->show($form->getHTML());
	}


	/**
	 *
	 */
	protected function updateConfigure() {
		$form = $this->getConfigurationForm();
		$form->setValuesByPost();

		if (!$form->checkInput()) {
			$this->show($form->getHTML());

			return;
		}

		$config = ilHelpMeConfig::getConfig();

		$recipient = $form->getInput("srsu_recipient");
		$config->setRecipient($recipient);

		$send_email_address = $form->getInput("srsu_send_email_address");
		$config->setSendEmailAddress($send_email_address);

		$jira_domain = $form->getInput("srsu_jira_domain");
		$config->setJiraDomain($jira_domain);

		$jira_project_key = $form->getInput("srsu_jira_project_key");
		$config->setJiraProjectKey($jira_project_key);

		$jira_issue_type = $form->getInput("srsu_jira_issue_type");
		$config->setJiraIssueType($jira_issue_type);

		$jira_authorization = $form->getInput("srsu_jira_authorization");
		$config->setJiraAuthorization($jira_authorization);

		$jira_username = $form->getInput("srsu_jira_username");
		$config->setJiraUsername($jira_username);

		$jira_password = $form->getInput("srsu_jira_password");
		$config->setJiraPassword($jira_password);

		$jira_consumer_key = $form->getInput("srsu_jira_consumer_key");
		$config->setJiraConsumerKey($jira_consumer_key);

		$jira_private_key = $form->getInput("srsu_jira_private_key");
		$config->setJiraPrivateKey($jira_private_key);

		$jira_access_token = $form->getInput("srsu_jira_access_token");
		$config->setJiraAccessToken($jira_access_token);

		$priorities = $form->getInput("srsu_priorities");
		ilHelpMeConfigPriority::setConfigPrioritiesArray($priorities);

		$info = $form->getInput("srsu_info");
		$config->setInfo($info);

		$roles = $form->getInput("srsu_roles");
		ilHelpMeConfigRole::setConfigRolesArray($roles);

		$config->update();

		ilUtil::sendSuccess($this->txt("srsu_configuration_saved"));

		$this->show($form->getHTML());
	}


	/**
	 * @param string $a_var
	 *
	 * @return string
	 */
	protected function txt($a_var) {
		return $this->pl->txt($a_var);
	}
}
