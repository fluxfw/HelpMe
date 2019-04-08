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
			$issue_types[] = $project->project_issue_type;
			$issue_types = array_unique($issue_types);

			$project->setProjectIssueTypes($issue_types);

			$project->store();
		}
	}

	\srag\DIC\HelpMe\DICStatic::dic()->database()->dropTableColumn(\srag\Plugins\HelpMe\Project\Project::TABLE_NAME, "project_issue_type");
}
?>
