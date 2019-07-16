<#1>
<?php
//
?>
<#2>
<?php
//
?>
<#3>
<?php
//
?>
<#4>
<?php
//
?>
<#5>
<?php
//
?>
<#6>
<?php
//
?>
<#7>
<?php
//
?>
<#8>
<?php
//
?>
<#9>
<?php
//
?>
<#10>
<?php
//
?>
<#11>
<?php
\srag\Plugins\HelpMe\Config\Config::updateDB();
\srag\Plugins\HelpMe\Project\Project::updateDB();
?>
<#12>
<?php
\srag\Plugins\HelpMe\Ticket\Ticket::updateDB();
?>
<#13>
<?php
\srag\Plugins\HelpMe\Project\Project::updateDB();

if (\srag\DIC\HelpMe\DICStatic::dic()->database()->tableColumnExists(\srag\Plugins\HelpMe\Project\Project::TABLE_NAME, "project_issue_type")) {

	foreach (\srag\Plugins\HelpMe\Project\Project::get() as $project) {
		/**
		 * @var \srag\Plugins\HelpMe\Project\Project $project
		 */

		if (!empty($project->project_issue_type)) {
			$issue_types = $project->getProjectIssueTypes();

			$issue_types[] = [
				"issue_type" => $project->project_issue_type,
				"fixed_version" => []
			];

			$project->setProjectIssueTypes($issue_types);

			$project->store();
		}
	}

	\srag\DIC\HelpMe\DICStatic::dic()->database()->dropTableColumn(\srag\Plugins\HelpMe\Project\Project::TABLE_NAME, "project_issue_type");
}
if (\srag\DIC\HelpMe\DICStatic::dic()->database()->tableColumnExists(\srag\Plugins\HelpMe\Project\Project::TABLE_NAME, "project_fix_version")) {

	foreach (\srag\Plugins\HelpMe\Project\Project::get() as $project) {
		/**
		 * @var \srag\Plugins\HelpMe\Project\Project $project
		 */

		if (!empty($project->project_fix_version)) {
			$issue_types = $project->getProjectIssueTypes();

			foreach ($issue_types as &$issue_type) {
				if (empty($issue_types["fixed_version"])) {
					$issue_types["fixed_version"] = $project->project_fix_version;
				}
			}

			$project->setProjectIssueTypes($issue_types);

			$project->store();
		}
	}

	\srag\DIC\HelpMe\DICStatic::dic()->database()->dropTableColumn(\srag\Plugins\HelpMe\Project\Project::TABLE_NAME, "project_fix_version");
}
?>
<#14>
<?php
\srag\Plugins\HelpMe\Notification\Notification\Notification::updateDB_();
\srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::updateDB_();

$templates = \srag\Plugins\HelpMe\Config\Config::getField(\srag\Plugins\HelpMe\Config\Config::KEY_RECIPIENT_TEMPLATES);

if (\srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->migrateFromOldGlobalPlugin($templates[\srag\Plugins\HelpMe\Recipient\RecipientCreateJiraTicket::SEND_EMAIL]) === null) {

	$notification = \srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->factory()->newInstance();

	$notification->setName($templates[\srag\Plugins\HelpMe\Recipient\RecipientCreateJiraTicket::SEND_EMAIL] = \srag\Plugins\HelpMe\Recipient\RecipientCreateJiraTicket::SEND_EMAIL);
	$notification->setTitle("Mail");
	$notification->setDefaultLanguage(\srag\DIC\HelpMe\DICStatic::dic()->language()->getDefaultLanguage() === "de" ? "de" : "en");

	foreach ([ "de", "en" ] as $lang) {
		$notification->setSubject("{{ support.getTitle }}", $lang);
		$notification->setText("{% for field in fields %}
<p>
	<h2>{{ field.getLabel }}</h2>
	{{ field.getValue }}
</p>
<br>
{% endfor %}", $lang);
	}

	\srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->storeInstance($notification);
}

if (\srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->migrateFromOldGlobalPlugin($templates[\srag\Plugins\HelpMe\Recipient\RecipientCreateJiraTicket::CREATE_JIRA_TICKET]) === null) {

	$notification = \srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->factory()->newInstance();

	$notification->setName($templates[\srag\Plugins\HelpMe\Recipient\RecipientCreateJiraTicket::CREATE_JIRA_TICKET] = \srag\Plugins\HelpMe\Recipient\RecipientCreateJiraTicket::CREATE_JIRA_TICKET);
	$notification->setTitle("Jira");
	$notification->setDefaultLanguage(\srag\DIC\HelpMe\DICStatic::dic()->language()->getDefaultLanguage() === "de" ? "de" : "en");

	foreach ([ "de", "en" ] as $lang) {
		$notification->setSubject("{{ support.getTitle }}", $lang);
		$notification->setText("{% for field in fields %}
{{ field.getLabel }}:
{{ field.getValue }}


{% endfor %}", $lang);
	}

	\srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->storeInstance($notification);
}

if (\srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->migrateFromOldGlobalPlugin($templates[\srag\Plugins\HelpMe\Config\Config::KEY_SEND_CONFIRMATION_EMAIL]) === null) {

	$notification = \srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->factory()->newInstance();

	$notification->setName($templates[\srag\Plugins\HelpMe\Config\Config::KEY_SEND_CONFIRMATION_EMAIL] = \srag\Plugins\HelpMe\Config\Config::KEY_SEND_CONFIRMATION_EMAIL);
	$notification->setTitle("Confirm Mail");
	$notification->setDefaultLanguage(\srag\DIC\HelpMe\DICStatic::dic()->language()->getDefaultLanguage() === "de" ? "de" : "en");

	foreach ([ "de", "en" ] as $lang) {
		$notification->setSubject(\srag\DIC\HelpMe\DICStatic::plugin(\ilHelpMePlugin::class)
				->translate("confirmation", \srag\Plugins\HelpMe\Support\SupportGUI::LANG_MODULE_SUPPORT, [], true, $lang)
			. ": {{ support.getTitle }}", $lang);
		$notification->setText("{% for field in fields %}
<p>
	<h2>{{ field.getLabel }}</h2>
	{{ field.getValue }}
</p>
<br>
{% endfor %}", $lang);
	}

	\srag\Notifications4Plugin\HelpMe\Notification\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Notification::class, \srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->storeInstance($notification);
}

\srag\Plugins\HelpMe\Config\Config::setField(\srag\Plugins\HelpMe\Config\Config::KEY_RECIPIENT_TEMPLATES, $templates);
?>
<#15>
<?php
foreach (\srag\Notifications4Plugin\HelpMe\Notification\Language\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
	         ->getLanguages() as $language) {
	$text = $language->getText();

	$text = preg_replace("/\{%\s+for\s+key,\s*value\s+in\s+fields\s+%\}/", "{% for field in fields %}", $text);
	$text = preg_replace("/{{\s+key\s+}}/", "{{ field.getLabel }}", $text);
	$text = preg_replace("/{{\s+value\s+}}/", "{{ field.getValue }}", $text);

	$language->setText($text);

	\srag\Notifications4Plugin\HelpMe\Notification\Language\Repository::getInstance(\srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::class)
		->storeInstance($language);
}
?>
<#16>
<?php
\srag\Plugins\HelpMe\Notification\Notification\Notification::updateDB_();
\srag\Plugins\HelpMe\Notification\Notification\Language\NotificationLanguage::updateDB_();
?>
