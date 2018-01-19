<#1>
<?php
	require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/Config/class.ilHelpMeConfig.php";
	require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/Config/class.ilHelpMeConfigPriority.php";
	require_once "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/HelpMe/classes/Config/class.ilHelpMeConfigRole.php";

	ilHelpMeConfig::updateDB();

	ilHelpMeConfigPriority::updateDB();

	ilHelpMeConfigRole::updateDB();
?>
