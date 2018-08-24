<#1>
<?php
\srag\Plugins\HelpMe\Config\HelpMeConfig::updateDB();

\srag\Plugins\HelpMe\Config\HelpMeConfigPriority::updateDB();

\srag\Plugins\HelpMe\Config\HelpMeConfigRole::updateDB();
?>
<#2>
<?php
\srag\Plugins\HelpMe\Config\HelpMeConfig::updateDB();

if (\srag\DIC\DICCache::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\HelpMeConfigOld::TABLE_NAME)) {
	$config = \srag\Plugins\HelpMe\Config\HelpMeConfigOld::getConfig();

	\srag\Plugins\HelpMe\Config\HelpMeConfig::setInfo($config->getInfo());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraAccessToken($config->getJiraAccessToken());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraAuthorization($config->getJiraAuthorization());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraConsumerKey($config->getJiraConsumerKey());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraDomain($config->getJiraDomain());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraIssueType($config->getJiraIssueType());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraPassword($config->getJiraPassword());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraPrivateKey($config->getJiraPrivateKey());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraProjectKey($config->getJiraProjectKey());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraUsername($config->getJiraUsername());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setRecipient($config->getRecipient());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setSendEmailAddress($config->getSendEmailAddress());

	\srag\DIC\DICCache::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\HelpMeConfigOld::TABLE_NAME);
}
?>
