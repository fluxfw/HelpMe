<?php

require_once "Services/Component/classes/class.ilPluginConfigGUI.php";
require_once "Services/Form/classes/class.ilPropertyFormGUI.php";
require_once "Services/Form/classes/class.ilRadioGroupInputGUI.php";
require_once "Services/Form/classes/class.ilRadioOption.php";
require_once "Services/Form/classes/class.ilTextInputGUI.php";
require_once "Services/Form/classes/class.ilEMailInputGUI.php";
require_once "Services/Form/classes/class.ilTextAreaInputGUI.php";
require_once "Services/Form/classes/class.ilMultiSelectInputGUI.php";
require_once "Services/Utilities/classes/class.ilUtil.php";

/**
 * HelpMe Config GUI
 */
class ilHelpMeConfigGUI extends ilPluginConfigGUI {

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
		/**
		 * var ilCtrl $ilCtrl
		 * var ilTemplate $tpl
		 */

		global $ilCtrl, $tpl;

		$this->ctrl = $ilCtrl;
		$this->pl = ilHelpMePlugin::getInstance();
		$this->tpl = $tpl;
	}


	/**
	 *
	 * @param string $cmd
	 */
	function performCommand($cmd) {
		switch ($cmd) {
			case "configure":
			case "updateConfigure":
				$this->$cmd();
				break;

			default:
				break;
		}
	}


	/**
	 *
	 * @return ilPropertyFormGUI
	 */
	protected function getConfigurationForm() {
		$config = $this->pl->getConfig();
		$configPriorities = $this->pl->getConfigPrioritiesArray();
		$allRoles = $this->pl->getRoles();
		$configRoles = $this->pl->getConfigRolesArray();

		$form = new ilPropertyFormGUI();

		$form->setFormAction($this->ctrl->getFormAction($this));

		$form->setTitle($this->txt("srsu_configuration"));

		$form->addCommandButton("updateConfigure", $this->txt("srsu_save"));

		$recipient = new ilRadioGroupInputGUI($this->txt("srsu_recipient"), "srsu_recipient");
		$recipient->setRequired(true);

		$recipient_email = new ilRadioOption();
		$recipient_email->setTitle($this->txt("srsu_send_email"));
		$recipient_email->setValue("send_email");

		$send_email_address = new ilEMailInputGUI($this->txt("srsu_email_address"), "srsu_send_email_address");
		$send_email_address->setRequired(true);
		$send_email_address->setValue($config->getSendEmailAddress());
		$recipient_email->addSubItem($send_email_address);

		$recipient->addOption($recipient_email);

		$recipient_jira = new ilRadioOption();
		$recipient_jira->setTitle($this->txt("srsu_create_jira_ticket"));
		$recipient_jira->setDisabled(true);
		$recipient_jira->setValue("create_jira_ticket");
		$recipient->addOption($recipient_jira);

		$recipient->setValue($config->getRecipient());

		$form->addItem($recipient);

		$priorities = new ilTextInputGUI($this->txt("srsu_priorities"), "srsu_priorities");
		$priorities->setMulti(true);
		$priorities->setRequired(true);
		$priorities->setValue($configPriorities);
		$form->addItem($priorities);

		$info = new ilTextAreaInputGUI($this->txt("srsu_info"), "srsu_info");
		$info->setRequired(true);
		$info->setValue($config->getInfo());
		$form->addItem($info);

		$roles = new ilMultiSelectInputGUI($this->txt("srsu_roles"), "srsu_roles");
		$roles->setRequired(true);
		$roles->setOptions($allRoles);
		$roles->setValue($configRoles);
		$roles->enableSelectAll(true);
		$form->addItem($roles);

		return $form;
	}


	protected function showForm($form) {
		$this->tpl->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function configure() {
		$form = $this->getConfigurationForm();

		$this->showForm($form);
	}


	/**
	 *
	 */
	protected function updateConfigure() {
		$form = $this->getConfigurationForm();
		$form->setValuesByPost();

		if (!$form->checkInput()) {
			$this->showForm($form);

			return;
		}

		$config = $this->pl->getConfig();

		$recipient = $form->getInput("srsu_recipient");
		$config->setRecipient($recipient);

		$send_email_address = $form->getInput("srsu_send_email_address");
		$config->setSendEmailAddress($send_email_address);

		$priorities = $form->getInput("srsu_priorities");
		$this->pl->setConfigPrioritiesArray($priorities);

		$info = $form->getInput("srsu_info");
		$config->setInfo($info);

		$roles = $form->getInput("srsu_roles");
		$this->pl->setConfigRolesArray($roles);

		$config->update();

		ilUtil::sendSuccess($this->txt("srsu_configuration_saved"));

		$this->showForm($form);
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
