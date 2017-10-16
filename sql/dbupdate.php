<#1>
<?php
	require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfig.php";
	require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfigPriorities.php";
	require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/HelpMe/class.ilHelpMeConfigRoles.php";

	ilHelpMeConfig::updateDB();
	ilHelpMeConfigPriorities::updateDB();
	ilHelpMeConfigRoles::updateDB();
?>
