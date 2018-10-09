<#1>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();

\srag\Plugins\HelpMe\Config\ConfigPriority::updateDB();

\srag\Plugins\HelpMe\Config\ConfigRole::updateDB();
?>
<#2>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();

if (\srag\DIC\DICStatic::dic()->database()->tableExists(\srag\Plugins\HelpMe\Config\ConfigOld::TABLE_NAME)) {
	$config_old = \srag\Plugins\HelpMe\Config\ConfigOld::getConfig();

	\srag\Plugins\HelpMe\Config\Config::setInfo($config_old->getInfo());
	\srag\Plugins\HelpMe\Config\Config::setJiraAccessToken($config_old->getJiraAccessToken());
	\srag\Plugins\HelpMe\Config\Config::setJiraAuthorization($config_old->getJiraAuthorization());
	\srag\Plugins\HelpMe\Config\Config::setJiraConsumerKey($config_old->getJiraConsumerKey());
	\srag\Plugins\HelpMe\Config\Config::setJiraDomain($config_old->getJiraDomain());
	\srag\Plugins\HelpMe\Config\Config::setJiraIssueType($config_old->getJiraIssueType());
	\srag\Plugins\HelpMe\Config\Config::setJiraPassword($config_old->getJiraPassword());
	\srag\Plugins\HelpMe\Config\Config::setJiraPrivateKey($config_old->getJiraPrivateKey());
	\srag\Plugins\HelpMe\Config\Config::setJiraProjectKey($config_old->getJiraProjectKey());
	\srag\Plugins\HelpMe\Config\Config::setJiraUsername($config_old->getJiraUsername());
	\srag\Plugins\HelpMe\Config\Config::setRecipient($config_old->getRecipient());
	\srag\Plugins\HelpMe\Config\Config::setSendEmailAddress($config_old->getSendEmailAddress());

	\srag\DIC\DICStatic::dic()->database()->dropTable(\srag\Plugins\HelpMe\Config\ConfigOld::TABLE_NAME);
}
?>
