<#1>
<?php
ilHelpMeConfig::updateDB();

ilHelpMeConfigPriority::updateDB();

ilHelpMeConfigRole::updateDB();
?>
<#2>
<?php
ilHelpMeConfig::updateDB();

if (\srag\DIC\DICCache::dic()->database()->tableExists(ilHelpMeConfigOld::TABLE_NAME)) {
	$config = ilHelpMeConfigOld::getConfig();

	ilHelpMeConfig::setInfo($config->getInfo());
	ilHelpMeConfig::setJiraAccessToken($config->getJiraAccessToken());
	ilHelpMeConfig::setJiraAuthorization($config->getJiraAuthorization());
	ilHelpMeConfig::setJiraConsumerKey($config->getJiraConsumerKey());
	ilHelpMeConfig::setJiraDomain($config->getJiraDomain());
	ilHelpMeConfig::setJiraIssueType($config->getJiraIssueType());
	ilHelpMeConfig::setJiraPassword($config->getJiraPassword());
	ilHelpMeConfig::setJiraPrivateKey($config->getJiraPrivateKey());
	ilHelpMeConfig::setJiraProjectKey($config->getJiraProjectKey());
	ilHelpMeConfig::setJiraUsername($config->getJiraUsername());
	ilHelpMeConfig::setRecipient($config->getRecipient());
	ilHelpMeConfig::setSendEmailAddress($config->getSendEmailAddress());

	\srag\DIC\DICCache::dic()->database()->dropTable(ilHelpMeConfigOld::TABLE_NAME);
}
?>
