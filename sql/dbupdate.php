<#1>
<?php
\srag\Plugins\HelpMe\Config\HelpMeConfig::updateDB();

\srag\Plugins\HelpMe\Config\HelpMeConfigPriority::updateDB();

\srag\Plugins\HelpMe\Config\HelpMeConfigRole::updateDB();
?>
<#2>
<?php
\srag\Plugins\HelpMe\Config\HelpMeConfig::updateDB();

if (\srag\DIC\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\HelpMeConfigOld::TABLE_NAME)) {
	$config_old = \srag\Plugins\HelpMe\Config\HelpMeConfigOld::getConfig();

	\srag\Plugins\HelpMe\Config\HelpMeConfig::setInfo($config_old->getInfo());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraAccessToken($config_old->getJiraAccessToken());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraAuthorization($config_old->getJiraAuthorization());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraConsumerKey($config_old->getJiraConsumerKey());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraDomain($config_old->getJiraDomain());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraIssueType($config_old->getJiraIssueType());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraPassword($config_old->getJiraPassword());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraPrivateKey($config_old->getJiraPrivateKey());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraProjectKey($config_old->getJiraProjectKey());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setJiraUsername($config_old->getJiraUsername());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setRecipient($config_old->getRecipient());
	\srag\Plugins\HelpMe\Config\HelpMeConfig::setSendEmailAddress($config_old->getSendEmailAddress());

	\srag\DIC\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\HelpMeConfigOld::TABLE_NAME);
}
?>
