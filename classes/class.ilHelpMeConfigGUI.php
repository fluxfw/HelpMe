<?php

require_once "Services/Component/classes/class.ilPluginConfigGUI.php";
require_once "Services/Form/classes/class.ilPropertyFormGUI.php";
require_once "Services/Form/classes/class.ilRadioGroupInputGUI.php";
require_once "Services/Form/classes/class.ilRadioOption.php";
require_once "Services/Form/classes/class.ilTextInputGUI.php";
require_once "Services/Form/classes/class.ilTextAreaInputGUI.php";
require_once "Services/Form/classes/class.ilMultiSelectInputGUI.php";
require_once "Services/Utilities/classes/class.ilUtil.php";

/**
 *
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

		$form->addCommandButton("updateConfigure", $this->txt("srsu_update"));

		$recipient = new ilRadioGroupInputGUI($this->txt("srsu_recipient"), "srsu_recipient");
		$recipient->setRequired(true);

		$recipient_email = new ilRadioOption();
		$recipient_email->setTitle($this->txt("srsu_send_email"));
		$recipient_email->setValue("send_email");

		$send_email_address = new ilTextInputGUI($this->txt("srsu_email_address"), "srsu_send_email_address");
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

		$info = new ilTextAreaInputGUI($this->txt("srsu_info"), "srsu_info");
		$info->setRequired(true);
		$info->setValue($config->getInfo());
		$form->addItem($info);

		$priorities = new ilTextInputGUI($this->txt("srsu_priorities"), "srsu_priorities");
		$priorities->setMulti(true);
		$priorities->setRequired(true);
		$priorities->setValue($configPriorities);
		$form->addItem($priorities);

		$roles = new ilMultiSelectInputGUI($this->txt("srsu_roles"), "srsu_roles");
		$roles->setRequired(true);
		$roles->setOptions($allRoles);
		$roles->setValue($configRoles);
		$roles->enableSelectAll(true);
		$form->addItem($roles);

		return $form;
	}


	/**
	 *
	 */
	protected function configure() {
		$form = $this->getConfigurationForm();

		$this->tpl->setContent($form->getHTML());
	}


	/**
	 *
	 */
	protected function updateConfigure() {
		$form = $this->getConfigurationForm();
		$form->setValuesByPost();

		if (!$form->checkInput()) {
			$this->tpl->setContent($form->getHTML());

			return;
		}

		$recipient = $form->getInput("srsu_recipient");
		$send_email_address = $form->getInput("srsu_send_email_address");
		$info = $form->getInput("srsu_info");
		$priorities = $form->getInput("srsu_priorities");
		$roles = $form->getInput("srsu_roles");

		$config = $this->pl->getConfig();

		$config->setRecipient($recipient);
		$config->setSendEmailAddress($send_email_address);
		$config->setInfo($info);
		$this->pl->setConfigPrioritiesArray($priorities);
		$this->pl->setConfigRolesArray($roles);

		$config->update();

		ilUtil::sendSuccess($this->txt("srsu_configuration_saved"));

		$this->tpl->setContent($form->getHTML());
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
