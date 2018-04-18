<?php

/**
 * HelpMe Config Form GUI
 */
class ilHelpMeConfigFormGUI extends ilPropertyFormGUI {

	/**
	 * @var ilHelpMeConfig
	 */
	protected $config;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilHelpMeConfigGUI
	 */
	protected $parent;
	/**
	 * @var ilHelpMePlugin
	 */
	protected $pl;


	/**
	 * @param ilHelpMeConfigGUI $parent
	 */
	public function __construct(ilHelpMeConfigGUI $parent) {
		parent::__construct();

		global $DIC;

		$this->config = ilHelpMeConfig::getConfig();
		$this->ctrl = $DIC->ctrl();
		$this->parent = $parent;
		$this->pl = ilHelpMePlugin::getInstance();

		$this->setForm();
	}


	/**
	 *
	 */
	protected function setForm() {
		$configPriorities = ilHelpMeConfigPriority::getConfigPrioritiesArray();
		$allRoles = ilHelpMeConfigRole::getAllRoles();
		$configRoles = ilHelpMeConfigRole::getConfigRolesArray();

		$this->setFormAction($this->ctrl->getFormAction($this->parent));

		$this->setTitle($this->txt("srsu_configuration"));

		$this->addCommandButton(ilHelpMeConfigGUI::CMD_UPDATE_CONFIGURE, $this->txt("srsu_save"));

		// Recipient
		$recipient = new ilRadioGroupInputGUI($this->txt("srsu_recipient"), "srsu_recipient");
		$recipient->setRequired(true);
		$recipient->setValue($this->config->getRecipient());
		$this->addItem($recipient);

		// Send email
		$recipient_email = new ilRadioOption($this->txt("srsu_send_email"), ilHelpMeRecipient::SEND_EMAIL);
		$recipient->addOption($recipient_email);

		$send_email_address = new ilEMailInputGUI($this->txt("srsu_email_address"), "srsu_send_email_address");
		$send_email_address->setRequired(true);
		$send_email_address->setValue($this->config->getSendEmailAddress());
		$recipient_email->addSubItem($send_email_address);

		// Create Jira ticket
		$recipient_jira = new ilRadioOption($this->txt("srsu_create_jira_ticket"), ilHelpMeRecipient::CREATE_JIRA_TICKET);
		$recipient->addOption($recipient_jira);

		$jira_domain = new ilTextInputGUI($this->txt("srsu_jira_domain"), "srsu_jira_domain");
		$jira_domain->setRequired(true);
		$jira_domain->setValue($this->config->getJiraDomain());
		$recipient_jira->addSubItem($jira_domain);

		$jira_project_key = new ilTextInputGUI($this->txt("srsu_jira_project_key"), "srsu_jira_project_key");
		$jira_project_key->setRequired(true);
		$jira_project_key->setValue($this->config->getJiraProjectKey());
		$recipient_jira->addSubItem($jira_project_key);

		$jira_issue_type = new ilTextInputGUI($this->txt("srsu_jira_issue_type"), "srsu_jira_issue_type");
		$jira_issue_type->setRequired(true);
		$jira_issue_type->setInfo("Task, Bug, ...");
		$jira_issue_type->setValue($this->config->getJiraIssueType());
		$recipient_jira->addSubItem($jira_issue_type);

		// Jira authorization
		$jira_authorization = new ilRadioGroupInputGUI($this->txt("srsu_jira_authorization"), "srsu_jira_authorization");
		$jira_authorization->setRequired(true);
		$jira_authorization->setValue($this->config->getJiraAuthorization());
		$recipient_jira->addSubItem($jira_authorization);

		// Username & Password
		$jira_authorization_userpassword = new ilRadioOption($this->txt("srsu_jira_usernamepassword"), ilJiraCurl::AUTHORIZATION_USERNAMEPASSWORD);
		$jira_authorization->addOption($jira_authorization_userpassword);

		$jira_username = new ilTextInputGUI($this->txt("srsu_jira_username"), "srsu_jira_username");
		$jira_username->setRequired(true);
		$jira_username->setValue($this->config->getJiraUsername());
		$jira_authorization_userpassword->addSubItem($jira_username);

		$jira_password = new ilPasswordInputGUI($this->txt("srsu_jira_password"), "srsu_jira_password");
		$jira_password->setRequired(true);
		$jira_password->setRetype(false);
		$jira_password->setValue($this->config->getJiraPassword());
		$jira_authorization_userpassword->addSubItem($jira_password);

		// oAuth
		$jira_oauth = new ilRadioOption($this->txt("srsu_jira_oauth"), ilJiraCurl::AUTHORIZATION_OAUTH);
		$jira_authorization->addOption($jira_oauth);

		$jira_consumer_key = new ilTextInputGUI($this->txt("srsu_jira_consumer_key"), "srsu_jira_consumer_key");
		$jira_consumer_key->setRequired(true);
		$jira_consumer_key->setValue($this->config->getJiraConsumerKey());
		$jira_oauth->addSubItem($jira_consumer_key);

		$jira_private_key = new ilTextAreaInputGUI($this->txt("srsu_jira_private_key"), "srsu_jira_private_key");
		$jira_private_key->setRequired(true);
		$jira_private_key->setInfo("PEM formatted RSA private key");
		$jira_private_key->setValue($this->config->getJiraPrivateKey());
		$jira_oauth->addSubItem($jira_private_key);

		$jira_access_token = new ilTextInputGUI($this->txt("srsu_jira_access_token"), "srsu_jira_access_token");
		$jira_access_token->setRequired(true);
		$jira_access_token->setValue($this->config->getJiraAccessToken());
		$jira_oauth->addSubItem($jira_access_token);

		// Priorities
		$priorities = new ilTextInputGUI($this->txt("srsu_priorities"), "srsu_priorities");
		$priorities->setMulti(true);
		$priorities->setRequired(true);
		$priorities->setValue($configPriorities);
		$this->addItem($priorities);

		// Info text
		$info = new ilTextAreaInputGUI($this->txt("srsu_info"), "srsu_info");
		$info->setRequired(true);
		$info->setUseRte(true);
		$info->setRteTagSet("extended");
		$info->setValue($this->config->getInfo());
		$this->addItem($info);

		// Roles
		$roles = new ilMultiSelectInputGUI($this->txt("srsu_roles"), "srsu_roles");
		$roles->setRequired(true);
		$roles->setInfo($this->txt("srsu_roles_description"));
		$roles->setOptions($allRoles);
		$roles->setValue($configRoles);
		$roles->enableSelectAll(true);
		$this->addItem($roles);
	}


	/**
	 *
	 */
	public function updateConfig() {
		$recipient = $this->getInput("srsu_recipient");
		$this->config->setRecipient($recipient);

		$send_email_address = $this->getInput("srsu_send_email_address");
		$this->config->setSendEmailAddress($send_email_address);

		$jira_domain = $this->getInput("srsu_jira_domain");
		$this->config->setJiraDomain($jira_domain);

		$jira_project_key = $this->getInput("srsu_jira_project_key");
		$this->config->setJiraProjectKey($jira_project_key);

		$jira_issue_type = $this->getInput("srsu_jira_issue_type");
		$this->config->setJiraIssueType($jira_issue_type);

		$jira_authorization = $this->getInput("srsu_jira_authorization");
		$this->config->setJiraAuthorization($jira_authorization);

		$jira_username = $this->getInput("srsu_jira_username");
		$this->config->setJiraUsername($jira_username);

		$jira_password = $this->getInput("srsu_jira_password");
		$this->config->setJiraPassword($jira_password);

		$jira_consumer_key = $this->getInput("srsu_jira_consumer_key");
		$this->config->setJiraConsumerKey($jira_consumer_key);

		$jira_private_key = $this->getInput("srsu_jira_private_key");
		$this->config->setJiraPrivateKey($jira_private_key);

		$jira_access_token = $this->getInput("srsu_jira_access_token");
		$this->config->setJiraAccessToken($jira_access_token);

		$priorities = $this->getInput("srsu_priorities");
		ilHelpMeConfigPriority::setConfigPrioritiesArray($priorities);

		$info = $this->getInput("srsu_info");
		$this->config->setInfo($info);

		$roles = $this->getInput("srsu_roles");
		ilHelpMeConfigRole::setConfigRolesArray($roles);

		$this->config->store();
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
