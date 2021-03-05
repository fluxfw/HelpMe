<?php

namespace srag\GeneratePluginInfosHelper\HelpMe;

use Closure;
use Composer\Config;
use Composer\Script\Event;

/**
 * Class UpdateVersion
 *
 * @package srag\GeneratePluginInfosHelper\HelpMe
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @internal
 */
final class UpdateVersion
{

    const CHANGELOG_INIT = "# Changelog" . self::LINE_BREAK_SEPARATOR . self::LINE_BREAK_SEPARATOR;
    const CHANGELOG_MD = "CHANGELOG.md";
    const CHANGELOG_TODO = "TODO";
    const CHANGELOG_TODO_AFTER = self::LINE_BREAK_SEPARATOR . self::LINE_BREAK_SEPARATOR;
    const CHANGELOG_TODO_BEFORE = "- ";
    const CHANGELOG_VERSION_AFTER = "]" . self::LINE_BREAK_SEPARATOR;
    const CHANGELOG_VERSION_BEFORE = "## [";
    const CHANGELOG_VERSION_X = "x";
    const COMPOSER_JSON = "composer.json";
    const LINE_BREAK_SEPARATOR = "\n";
    const UPDATE_TYPE_AUTO = 0;
    const UPDATE_TYPE_MAJOR = 2;
    const UPDATE_TYPE_MINOR = 3;
    const UPDATE_TYPE_NONE = 1;
    const UPDATE_TYPE_PATCH = 4;
    const VERSION_INIT = "1" . self::VERSION_SEPARATOR . "0" . self::VERSION_SEPARATOR . "0";
    const VERSION_SEPARATOR = ".";
    /**
     * @var self|null
     */
    private static $instance = null;


    /**
     * UpdateVersion constructor
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
     * @param Event $event
     *
     * @internal
     */
    public static function updateVersion(Event $event)/*: void*/
    {
        $project_root = rtrim(Closure::bind(function () : string {
            return $this->baseDir;
        }, $event->getComposer()->getConfig(), Config::class)(), "/");

        self::getInstance()->doUpdateVersion($project_root, intval($event->getArguments()[0] ?? self::UPDATE_TYPE_AUTO), strval($event->getArguments()[1] ?? ""), true);
    }


    /**
     * @param string      $project_root
     * @param int         $update_type
     * @param string|null $todo_changelog
     * @param bool        $log
     */
    public function doUpdateVersion(string $project_root, int $update_type = self::UPDATE_TYPE_AUTO, /*?*/ string $todo_changelog = null, bool $log = false)/*: void*/
    {
        $old_composer_json = file_get_contents($project_root . "/" . self::COMPOSER_JSON);
        $composer_json = json_decode($old_composer_json, true);

        if (file_exists($project_root . "/" . self::CHANGELOG_MD)) {
            $old_changelog = file_get_contents($project_root . "/" . self::CHANGELOG_MD);
        } else {
            $old_changelog = "";
        }
        $new_changelog = $old_changelog;

        $header_pos = strpos($new_changelog, self::CHANGELOG_INIT);
        if ($header_pos === false) {
            if ($log) {
                echo "Missing header in " . self::CHANGELOG_MD . " - Added it
";
            }
            $new_changelog = self::CHANGELOG_INIT . $new_changelog;
        } else {
            if ($header_pos !== 0) {
                if ($log) {
                    echo "WARNING: Header not found on first line in " . self::CHANGELOG_MD . " - Aborting
";
                    exit(1);
                }
            }
        }

        $version = strval($composer_json["version"] ?? "");
        if (!empty($version)) {
            if ($log) {
                echo "Use version " . $version . " from " . self::COMPOSER_JSON . "
";
            }
        } else {
            if ($log) {
                echo "Version is missing in " . self::COMPOSER_JSON . " - Use version " . self::VERSION_INIT . "
";
            }
            $version = self::VERSION_INIT;
            $update_type = self::UPDATE_TYPE_NONE;
        }

        $last_changelog_version_line = explode(self::LINE_BREAK_SEPARATOR, $new_changelog)[substr_count(self::CHANGELOG_INIT, self::LINE_BREAK_SEPARATOR)];

        if (!empty($last_changelog_version_line)) {
            $last_changelog_version_line .= self::LINE_BREAK_SEPARATOR;

            $last_changelog_version_line_start_pos = strlen(self::CHANGELOG_VERSION_BEFORE);
            $last_changelog_version_line_end_pos = (strlen($last_changelog_version_line) - strlen(self::CHANGELOG_VERSION_AFTER));

            if (strpos($last_changelog_version_line, self::CHANGELOG_VERSION_BEFORE) === 0
                && strrpos($last_changelog_version_line, self::CHANGELOG_VERSION_AFTER) === $last_changelog_version_line_end_pos
            ) {
                $last_changelog_version = substr($last_changelog_version_line, $last_changelog_version_line_start_pos, ($last_changelog_version_line_end_pos - $last_changelog_version_line_start_pos));

                if (version_compare($last_changelog_version, $version, ">")) {
                    if ($log) {
                        echo "Use newer version " . $last_changelog_version . " from " . self::CHANGELOG_MD . "
";
                    }
                    $version = $last_changelog_version;
                    $update_type = self::UPDATE_TYPE_NONE;
                }
            }
        }

        if ($update_type !== self::UPDATE_TYPE_NONE) {
            if ($update_type === self::UPDATE_TYPE_AUTO) {
                $update_type = self::UPDATE_TYPE_PATCH;
            }

            $version_parts = explode(self::VERSION_SEPARATOR, $version);

            switch ($update_type) {
                case self::UPDATE_TYPE_MAJOR:
                    $version_part_pos = 0;
                    break;

                case self::UPDATE_TYPE_MINOR:
                    $version_part_pos = 1;
                    break;

                case self::UPDATE_TYPE_PATCH:
                default:
                    $version_part_pos = 2;
                    break;
            }

            $version_parts[$version_part_pos] = intval($version_parts[$version_part_pos] ?? 0) + 1;
            for ($i = $version_part_pos + 1; $i < max(count($version_parts), count(explode(self::VERSION_SEPARATOR, self::VERSION_INIT))); $i++) {
                $version_parts[$i] = 0;
            }
            $version = implode(self::VERSION_SEPARATOR, $version_parts);
            if ($log) {
                echo "Update version to " . $version . "
";
            }
        } else {
            if ($log) {
                echo "Skip updating version
";
            }
        }

        $composer_json["version"] = $version;

        if (strpos($new_changelog, self::CHANGELOG_VERSION_BEFORE . self::CHANGELOG_VERSION_X . self::CHANGELOG_VERSION_AFTER) !== false) {
            if ($log) {
                echo "Replace " . self::CHANGELOG_VERSION_X . " version in " . self::CHANGELOG_MD . "
";
            }
            $new_changelog = str_replace(self::CHANGELOG_VERSION_BEFORE . self::CHANGELOG_VERSION_X . self::CHANGELOG_VERSION_AFTER,
                self::CHANGELOG_VERSION_BEFORE . $version . self::CHANGELOG_VERSION_AFTER, $new_changelog);
        } else {
            if (strpos($new_changelog, self::CHANGELOG_VERSION_BEFORE . $version . self::CHANGELOG_VERSION_AFTER) === false) {
                if ($log) {
                    echo "Add version " . $version . " to " . self::CHANGELOG_MD . "
";
                }

                if (empty($todo_changelog)) {
                    $todo_changelog = self::CHANGELOG_TODO;
                }

                $new_changelog = self::CHANGELOG_INIT . self::CHANGELOG_VERSION_BEFORE . $version . self::CHANGELOG_VERSION_AFTER . self::CHANGELOG_TODO_BEFORE . $todo_changelog
                    . self::CHANGELOG_TODO_AFTER . substr($new_changelog,
                        strlen(self::CHANGELOG_INIT));
            }
        }

        $new_changelog = rtrim($new_changelog, self::LINE_BREAK_SEPARATOR) . self::LINE_BREAK_SEPARATOR;

        $new_composer_json = preg_replace_callback("/\n( +)/", function (array $matches) : string {
                return "
" . str_repeat(" ", (strlen($matches[1]) / 2));
            }, json_encode($composer_json, JSON_UNESCAPED_SLASHES + JSON_PRETTY_PRINT)) . "
";

        if ($old_composer_json !== $new_composer_json) {
            if ($log) {
                echo "Store changes in " . self::COMPOSER_JSON . "
";
            }

            file_put_contents($project_root . "/" . self::COMPOSER_JSON, $new_composer_json);
        } else {
            if ($log) {
                echo "No changes in " . self::COMPOSER_JSON . "
";
            }
        }

        if ($old_changelog !== $new_changelog) {
            if ($log) {
                echo "Store changes in " . self::CHANGELOG_MD . "
";
            }

            file_put_contents($project_root . "/" . self::CHANGELOG_MD, $new_changelog);
        } else {
            if ($log) {
                echo "No changes in " . self::CHANGELOG_MD . "
";
            }
        }
    }
}
