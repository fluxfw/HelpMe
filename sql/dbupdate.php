<#1>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();
?>
<#2>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();

if (\srag\DIC\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ConfigOld::TABLE_NAME)) {
	\srag\Plugins\HelpMe\Config\ConfigOld::updateDB();

	$config_old = \srag\Plugins\HelpMe\Config\ConfigOld::getConfig();

	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_INFO, $config_old->getInfo());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_ACCESS_TOKEN, $config_old->getJiraAccessToken());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_AUTHORIZATION, $config_old->getJiraAuthorization());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_CONSUMER_KEY, $config_old->getJiraConsumerKey());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_DOMAIN, $config_old->getJiraDomain());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_ISSUE_TYPE, $config_old->getJiraIssueType());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PASSWORD, $config_old->getJiraPassword());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PRIVATE_KEY, $config_old->getJiraPrivateKey());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PROJECT_KEY, $config_old->getJiraProjectKey());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_USERNAME, $config_old->getJiraUsername());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_RECIPIENT, $config_old->getRecipient());
	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_SEND_EMAIL_ADDRESS, $config_old->getSendEmailAddress());

	\srag\DIC\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ConfigOld::TABLE_NAME);
}
?>
<#3>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();

if (\srag\DIC\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ConfigPriorityOld::TABLE_NAME)) {
	\srag\Plugins\HelpMe\Config\ConfigPriorityOld::updateDB();

	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_PRIORITIES, array_values(\srag\Plugins\HelpMe\Config\ConfigPriorityOld::getConfigPrioritiesArray()));

	\srag\DIC\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ConfigPriorityOld::TABLE_NAME);
}

if (\srag\DIC\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ConfigRoleOld::TABLE_NAME)) {
	\srag\Plugins\HelpMe\Config\ConfigRoleOld::updateDB();

	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_ROLES, array_values(\srag\Plugins\HelpMe\Config\ConfigRoleOld::getConfigRolesArray()));

	\srag\DIC\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ConfigRoleOld::TABLE_NAME);
}
?>
<#4>
<?php
$jira_project_key = \srag\Plugins\HelpMe\Config\Config::getField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PROJECT_KEY);

if (!empty($jira_project_key)) {
	$projects = \srag\Plugins\HelpMe\Config\Config::getField(\srag\Plugins\HelpMe\Config\Config::KEY_PROJECTS);

	$projects[$jira_project_key] = $jira_project_key;

	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_PROJECTS, $projects);

	\srag\Plugins\HelpMe\Config\Config::removeField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PROJECT_KEY);
}
?>
