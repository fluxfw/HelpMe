<#1>
<?php
\srag\Plugins\HelpMe\Config\ilHelpMeConfig::updateDB();

\srag\Plugins\HelpMe\Config\ilHelpMeConfigPriority::updateDB();

\srag\Plugins\HelpMe\Config\ilHelpMeConfigRole::updateDB();
?>
<#2>
<?php
\srag\Plugins\HelpMe\Config\ilHelpMeConfig::updateDB();

if (\srag\DIC\DICCache::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ilHelpMeConfigOld::TABLE_NAME)) {
	$config = \srag\Plugins\HelpMe\Config\ilHelpMeConfigOld::getConfig();

	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setInfo($config->getInfo());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraAccessToken($config->getJiraAccessToken());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraAuthorization($config->getJiraAuthorization());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraConsumerKey($config->getJiraConsumerKey());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraDomain($config->getJiraDomain());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraIssueType($config->getJiraIssueType());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraPassword($config->getJiraPassword());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraPrivateKey($config->getJiraPrivateKey());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraProjectKey($config->getJiraProjectKey());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setJiraUsername($config->getJiraUsername());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setRecipient($config->getRecipient());
	\srag\Plugins\HelpMe\Config\ilHelpMeConfig::setSendEmailAddress($config->getSendEmailAddress());

	\srag\DIC\DICCache::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ilHelpMeConfigOld::TABLE_NAME);
}
?>
