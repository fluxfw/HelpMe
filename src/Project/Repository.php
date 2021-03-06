<?php

namespace srag\Plugins\HelpMe\Project;

use ilDBConstants;
use ilHelpMePlugin;
use srag\DIC\HelpMe\DICTrait;
use srag\Plugins\HelpMe\Config\ConfigFormGUI;
use srag\Plugins\HelpMe\Support\Recipient\RecipientCreateJiraTicket;
use srag\Plugins\HelpMe\Support\SupportGUI;
use srag\Plugins\HelpMe\Utils\HelpMeTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\HelpMe\Project
 */
final class Repository
{

    use DICTrait;
    use HelpMeTrait;

    const PLUGIN_CLASS_NAME = ilHelpMePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @param Project $project
     */
    public function deleteProject(Project $project) : void
    {
        $project->delete();
    }


    /**
     * @internal
     */
    public function dropTables() : void
    {
        self::dic()->database()->dropTable(Project::TABLE_NAME, false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param Project $project
     * @param string  $issue_type
     *
     * @return string
     */
    public function getFixVersionForIssueType(Project $project, string $issue_type) : string
    {
        foreach ($project->getProjectIssueTypes() as $issue_type_) {
            if ($issue_type_["issue_type"] === $issue_type) {
                return strval($issue_type_["fix_version"]);
            }
        }

        return "";
    }


    /**
     * @param Project $project
     *
     * @return array
     */
    public function getIssueTypesOptions(Project $project) : array
    {
        $options = [];

        foreach ($project->getProjectIssueTypes() as $issue_type) {
            $options[$issue_type["issue_type"]] = $issue_type["issue_type"];
        }

        return $options;
    }


    /**
     * @param int $project_id
     *
     * @return Project|null
     */
    public function getProjectById(int $project_id) : ?Project
    {
        /**
         * @var Project|null $project
         */

        $project = Project::where(["project_id" => $project_id])->first();

        return $project;
    }


    /**
     * @param string $project_key
     *
     * @return Project|null
     */
    public function getProjectByKey(string $project_key) : ?Project
    {
        /**
         * @var Project|null $project
         */

        $project = Project::where(["project_key" => $project_key])->first();

        return $project;
    }


    /**
     * @param string $project_url_key
     *
     * @return Project|null
     */
    public function getProjectByUrlKey(string $project_url_key) : ?Project
    {
        /**
         * @var Project|null $project
         */

        $project = Project::where(["project_url_key" => $project_url_key])->first();

        return $project;
    }


    /**
     * @param bool $only_show_tickets
     *
     * @return Project[]
     */
    public function getProjects(bool $only_show_tickets = false) : array
    {
        $where = Project::where([]);

        if ($only_show_tickets) {
            $where = $where->where(["project_show_tickets" => true]);
        }

        return $where->orderBy("project_name", "ASC")->get();
    }


    /**
     * @param bool $only_with_show_tickets
     *
     * @return array
     */
    public function getProjectsOptions(bool $only_with_show_tickets = false) : array
    {
        return array_reduce($this->getProjects($only_with_show_tickets), function (array $projects, Project $project) : array {
            $projects[$project->getProjectUrlKey()] = $project->getProjectName();

            return $projects;
        }, []);
    }


    /**
     * @return bool
     */
    public function hasOneProjectAtLeastReadAccess() : bool
    {
        $result = self::dic()->database()->queryF("SELECT COUNT(project_show_tickets) AS count FROM " . Project::TABLE_NAME
            . " WHERE project_show_tickets=%s", [ilDBConstants::T_INTEGER], [true]);

        if (($row = $result->fetchAssoc()) !== false) {
            return (intval($row["count"]) > 0);
        }

        return false;
    }


    /**
     * @internal
     */
    public function installTables() : void
    {
        Project::updateDB();

        if (self::dic()->database()->tableColumnExists(Project::TABLE_NAME, "project_issue_type")) {

            foreach (Project::get() as $project) {
                /**
                 * @var Project $project
                 */

                if (!empty($project->project_issue_type)) {
                    $issue_types = $project->getProjectIssueTypes();

                    $issue_types[] = [
                        "issue_type"    => $project->project_issue_type,
                        "fixed_version" => []
                    ];

                    $project->setProjectIssueTypes($issue_types);

                    $this->storeProject($project);
                }
            }

            self::dic()->database()->dropTableColumn(Project::TABLE_NAME, "project_issue_type");
        }
        if (self::dic()->database()->tableColumnExists(Project::TABLE_NAME, "project_fix_version")) {

            foreach (Project::get() as $project) {
                /**
                 * @var Project $project
                 */

                if (!empty($project->project_fix_version)) {
                    $issue_types = $project->getProjectIssueTypes();

                    foreach ($issue_types as &$issue_type) {
                        if (empty($issue_types["fixed_version"])) {
                            $issue_types["fixed_version"] = $project->project_fix_version;
                        }
                    }

                    $project->setProjectIssueTypes($issue_types);

                    $this->storeProject($project);
                }
            }

            self::dic()->database()->dropTableColumn(Project::TABLE_NAME, "project_fix_version");
        }

        self::helpMe()->notifications4plugin()->notifications()->installTables();

        $templates = self::helpMe()->config()->getValue(ConfigFormGUI::KEY_RECIPIENT_TEMPLATES);

        if (!isset($templates[RecipientCreateJiraTicket::SEND_EMAIL])
            && self::helpMe()->notifications4plugin()->notifications()
                ->migrateFromOldGlobalPlugin($templates[RecipientCreateJiraTicket::SEND_EMAIL]) === null
        ) {

            $notification = self::helpMe()->notifications4plugin()->notifications()
                ->factory()->newInstance();

            $notification->setName($templates[RecipientCreateJiraTicket::SEND_EMAIL] = RecipientCreateJiraTicket::SEND_EMAIL);
            $notification->setTitle("Mail");

            foreach (["default", "de", "en"] as $lang) {
                $notification->setSubject("{{ support.title }}", $lang);
                $notification->setText("{% for field in fields %}
<p>
	<h2>{{ field.label |e }}</h2>
	{{ field.value |e }}
</p>
<br>
{% endfor %}", $lang);
            }

            self::helpMe()->notifications4plugin()->notifications()
                ->storeNotification($notification);
        }

        if (!isset($templates[RecipientCreateJiraTicket::CREATE_JIRA_TICKET])
            && self::helpMe()->notifications4plugin()->notifications()
                ->migrateFromOldGlobalPlugin($templates[RecipientCreateJiraTicket::CREATE_JIRA_TICKET]) === null
        ) {

            $notification = self::helpMe()->notifications4plugin()->notifications()
                ->factory()->newInstance();

            $notification->setName($templates[RecipientCreateJiraTicket::CREATE_JIRA_TICKET] = RecipientCreateJiraTicket::CREATE_JIRA_TICKET);
            $notification->setTitle("Jira");

            foreach (["default", "de", "en"] as $lang) {
                $notification->setSubject("{{ support.title }}", $lang);
                $notification->setText("{% for field in fields %}
{{ field.label |e }}:
{{ field.value |e }}


{% endfor %}", $lang);
            }

            self::helpMe()->notifications4plugin()->notifications()
                ->storeNotification($notification);
        }

        if (!isset($templates[ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL])
            && self::helpMe()->notifications4plugin()->notifications()
                ->migrateFromOldGlobalPlugin($templates[ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL]) === null
        ) {

            $notification = self::helpMe()->notifications4plugin()->notifications()
                ->factory()->newInstance();

            $notification->setName($templates[ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL] = ConfigFormGUI::KEY_SEND_CONFIRMATION_EMAIL);
            $notification->setTitle("Confirm Mail");

            foreach (["default", "de", "en"] as $lang) {
                $notification->setSubject(self::plugin()
                        ->translate("confirmation", SupportGUI::LANG_MODULE, [], true, $lang)
                    . ": {{ support.title }}", $lang);
                $notification->setText("{% for field in fields %}
<p>
	<h2>{{ field.label |e }}</h2>
	{{ field.value |e }}
</p>
<br>
{% endfor %}", $lang);
            }

            self::helpMe()->notifications4plugin()->notifications()
                ->storeNotification($notification);
        }

        self::helpMe()->config()->setValue(ConfigFormGUI::KEY_RECIPIENT_TEMPLATES, $templates);

        foreach (
            self::helpMe()->notifications4plugin()->notifications()
                ->getNotifications() as $notification
        ) {
            foreach (array_keys($notification->getTexts()) as $lang_key) {
                $subject = $notification->getSubject($lang_key, false);
                $text = $notification->getText($lang_key, false);

                $text = preg_replace("/\{%\s+for\s+key,\s*value\s+in\s+fields\s+%\}/", "{% for field in fields %}", $text);
                $text = preg_replace("/{{\s*key\s*}}/", "{{ field.label |e }}", $text);
                $text = preg_replace("/{{\s*field\.getLabel\s*}}/", "{{ field.label |e }}", $text);
                $text = preg_replace("/{{\s*value\s*}}/", "{{ field.value |e }}", $text);
                $text = preg_replace("/{{\s*field\.getValue\s*}}/", "{{ field.value |e }}", $text);

                foreach ([&$subject, &$text] as &$var) {
                    $var = preg_replace("/{{\s*support\.getTitle\s*}}/", "{{ support.title |e }}", $var);
                    $var = preg_replace("/{{\s*support\.getDescription\s*}}/", "{{ support.description |e }}", $var);
                    $var = preg_replace("/{{\s*support\.getPageReference\s*}}/", "{{ support.page_reference |e }}", $var);
                }

                $notification->setSubject($subject, $lang_key);
                $notification->setText($text, $lang_key);
            }

            self::helpMe()->notifications4plugin()->notifications()
                ->storeNotification($notification);
        }
    }


    /**
     * @param Project $project
     */
    public function storeProject(Project $project) : void
    {
        $project->store();
    }
}
