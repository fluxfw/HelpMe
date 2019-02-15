<#1>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();
?>
<#2>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();

if (\srag\DIC\HelpMe\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ConfigOld::TABLE_NAME)) {
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

	\srag\DIC\HelpMe\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ConfigOld::TABLE_NAME);
}
?>
<#3>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();

if (\srag\DIC\HelpMe\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ConfigPriorityOld::TABLE_NAME)) {
	\srag\Plugins\HelpMe\Config\ConfigPriorityOld::updateDB();

	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_PRIORITIES, array_values(\srag\Plugins\HelpMe\Config\ConfigPriorityOld::getConfigPrioritiesArray()));

	\srag\DIC\HelpMe\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ConfigPriorityOld::TABLE_NAME);
}

if (\srag\DIC\HelpMe\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ConfigRoleOld::TABLE_NAME)) {
	\srag\Plugins\HelpMe\Config\ConfigRoleOld::updateDB();

	\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_ROLES, array_values(\srag\Plugins\HelpMe\Config\ConfigRoleOld::getConfigRolesArray()));

	\srag\DIC\HelpMe\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ConfigRoleOld::TABLE_NAME);
}
?>
<#4>
<?php
$jira_project_key = \srag\Plugins\HelpMe\Config\Config::getField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PROJECT_KEY);

if (!empty($jira_project_key)) {
	$project = new \srag\Plugins\HelpMe\Project\Project();

	$project->setProjectKey($jira_project_key);

	$project->setProjectName($jira_project_key);

	$project->store();
}

\srag\Plugins\HelpMe\Config\Config::removeField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PROJECT_KEY);
?>
<#5>
<?php
\srag\Plugins\HelpMe\Project\Project::updateDB();
?>
<#6>
<?php
$projects = \srag\Plugins\HelpMe\Config\Config::getField(\srag\Plugins\HelpMe\Config\Config::KEY_PROJECTS);

if (is_array($projects)) {
	foreach ($projects as $project_key => $project_name) {
		$project = new \srag\Plugins\HelpMe\Project\Project();

		$project->setProjectKey($project_key);

		$project->setProjectName($project_name);

		$project->store();
	}
}

\srag\Plugins\HelpMe\Config\Config::removeField(\srag\Plugins\HelpMe\Config\Config::KEY_PROJECTS);
?>
<#7>
<?php
\srag\Plugins\HelpMe\Config\Config::removeField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_PROJECT_KEY);
\srag\Plugins\HelpMe\Config\Config::removeField(\srag\Plugins\HelpMe\Config\Config::KEY_PROJECTS);
?>
<#8>
<?php
\srag\Plugins\HelpMe\Project\Project::updateDB();

$jira_issue_key = \srag\Plugins\HelpMe\Config\Config::getField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_ISSUE_TYPE);

if (!empty($jira_issue_key)) {
	foreach (\srag\Plugins\HelpMe\Project\Projects::getInstance()->getProjects() as $project) {
		if (empty($project->getProjectIssueType())) {
			$project->setProjectIssueType($jira_issue_key);

			$project->store();
		}
	}
}

\srag\Plugins\HelpMe\Config\Config::removeField(\srag\Plugins\HelpMe\Config\Config::KEY_JIRA_ISSUE_TYPE);
?>
